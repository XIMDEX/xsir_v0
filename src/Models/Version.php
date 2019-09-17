<?php

namespace Ximdex\Models;

use Ximdex\Core\Database\Eloquent\Model;

class Version extends Model
{
    protected $hidden = [
        'id',
        'node_id',
        'node',
        'updated_at'
    ];
    
    protected $fillable = [
        'node_id'
    ];
    
    /**
     * Retrieve the node associated to this version
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function node()
    {
        return $this->belongsTo(Node::class);
    }
    
    /**
     * Generate a new version for a node given
     * 
     * @param Node $node
     * @param bool $newMajor
     * @throws \UnexpectedValueException
     * @return bool
     * @deprecated Use instead increaseMinor and increaseMajor
     */
    public static function generate(Node $node, bool $newMajor = false): bool
    {
        if (! $node->id) {
            throw new \UnexpectedValueException('Node ID must be provided to generate a new version');
        }
        $version = new static();
        $version->node()->associate($node);
        if ($node->version) {
            
             // A previous version exists for the related node 
            if ($newMajor) {
                
                // Only publishable nodes can generate a major version
                if (! $node->isPublishableProperty) {
                    throw new \Exception('This node must be pusblishable to generate a major version');
                }
                
                // The major value must be increased and the minor must be reset
                $node->version->major = $node->version->major + 1;
                $node->version->minor = 0;
            } else {
                
                // The minor value must be increased
                $version->minor = $node->version->minor + 1;
                $version->file = self::generateFileId($node->id);
            }
        } elseif ($newMajor) {
            
            // There is not a previous version and major version needs one
            throw new \Exception('A previous version is needed to generate a new major version');
        } else {
            
            // This is the first version with default values
            $version->file = self::generateFileId($node->id);
        }
        return $version->save();
    }
    
    /**
     * Generate a version for a node given with a new file and minor value increased
     * 
     * @param Node $node
     * @throws \UnexpectedValueException
     * @return bool
     */
    public static function increaseMinor(Node $node): bool
    {
        if (! $node->id) {
            throw new \UnexpectedValueException('Node ID must be provided to generate a new draft version');
        }
        $version = new static();
        $version->node()->associate($node);
        $version->file = self::generateFileId($node->id);
        if ($previousVersion = $node->version) {
            
            // A previous version exists for the related node, increase this minor 
            $version->minor = $previousVersion->minor + 1;
        }
        return $version->save();
    }
    
    /**
     * Update the current version of a node to a publishable version increasing major number and setting to zero the minor value
     * 
     * @param Node $node
     * @throws \UnexpectedValueException
     * @throws \Exception
     * @return bool
     */
    public static function increaseMajor(Node $node): bool
    {
        if (! $node->id) {
            throw new \UnexpectedValueException('Node ID must be provided to generate a new publishable version');
        }
        if (! $node->isPublishableProperty) {
            
            // Only publishable nodes can generate a major version
            throw new \Exception('This node must be pusblishable to generate a major version');
        }
        $version = $node->version;
        if (! $version) {
            
            // There is not a previous version and a major version needs one
            throw new \Exception('A previous version is needed to generate a new major version');
        }
        if ($version->minor == 0 and $version->major > 0) {
            
            // Already exists a ready to publish version
            throw new \Exception('Current version for this node is already ready to publish');
        }
        
        // The major value must be increased and the minor must be reset
        $version->major++;
        $version->minor = 0;
        return $version->save();
    }
    
    /**
     * Delete this version and the related document in the storage system
     * 
     * {@inheritDoc}
     * @see \Illuminate\Database\Eloquent\Model::delete()
     */
    public function delete()
    {
       // TODO Delete the related file before
       return parent::delete();
    }
    
    /**
     * Generates a unique hash ID for file field based in the node unique identificator 
     * 
     * @param int $nodeId
     * @throws \UnexpectedValueException
     * @return string
     */
    private static function generateFileId(int $nodeId): string
    {
        if ($nodeId <= 0) {
            throw new \UnexpectedValueException('Node ID must be greater than zero to generate a version file hash');
        }
        return uniqid($nodeId);
    }
}
