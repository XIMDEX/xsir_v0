<?php

namespace Ximdex\Models;

use Ximdex\Core\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
     * Generate a new version for a node given or a related node instead
     * 
     * @param Node $node
     * @throws ModelNotFoundException
     * @throws \UnexpectedValueException
     * @return bool
     */
    public static function generate(Node $node): bool
    {
        if (! $node->id) {
            throw new \UnexpectedValueException('Node ID must be provided to generate a new version');
        }
        $version = new static();
        $version->node()->associate($node);
        $version->file = uniqid($node->id);
        if ($node->version) {
            
             // A previous version exists for the related node, so the minor value must be increased
             $version->minor = $node->version->minor + 1;
        }
        return $version->save();
    }
    
    /**
     * {@inheritDoc}
     * @see \Illuminate\Database\Eloquent\Model::delete()
     */
    public function delete()
    {
       // TODO delete the related file before
       return parent::delete();
    }
}
