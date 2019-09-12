<?php

namespace Ximdex\Models;

use Illuminate\Support\Facades\DB;
use Ximdex\Scopes\NodeTypeScope;
use Ximdex\Core\Database\Eloquent\Model;

class Node extends Model
{
    /**
     * This property overwrite the inherited classes to save always the data in the Nodes table
     * 
     * @var string
     */
    protected $table = 'nodes';
    
    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'parent',
        'name',
    ];

    /**
     * @inheritDoc
     */
    protected $hidden = [
        'node_type',
        'node_type_id'
    ];

    /**
     * @inheritDoc
     */
    protected $appends = [
        'properties',
        'type',
        'version'
    ];

    /**
     * Set basic properties to the node
     *
     * @var array
     */
    protected $nodeProperties = [
        'icon' => "nodes",
        'isHidden' => true,
        'isPublishable' => false,
        'isCacheable' => true,
        'isVersionable' => false
    ];

    protected $_relations = [
        'node_type' => [
            'type' => 'belongsTo',
            'model' => NodeType::class
        ],
        'versions' => [
            'type' => 'hasMany',
            'model' => Version::class,
            'foreignKey' => 'node_id',
            'localKey' => 'id'
        ]
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->attributes = array_merge($this->attributes, [
            'node_type_id' => NodeType::where('type', class_basename(static::class))->select('id')->first()->id
        ]);
    }

    public function getPropertiesAttribute() : array
    {
        if (self::class != Node::class) {
            $class = "{$this->node_type->namespace}\\{$this->type}";
            $result = (new $class)->nodeProperties;
            $result['icon'] = config("xsir.icons.{$result['icon']}", $result['icon']);
            return $result;
        }
        return $this->nodeProperties;
    }

    public function getIconPropertyAttribute(): string
    {
        return $this->nodeProperties['icon'];
    }

    public function getIsHiddenPropertyAttribute(): bool
    {
        return $this->nodeProperties['isHidden'];
    }
    
    public function getIsVersionablePropertyAttribute(): bool
    {
        return $this->nodeProperties['isVersionable'];
    }

    public function getTypeAttribute()
    {
        return $this->node_type->type;
    }

    /**
     * Return the last version for this node, or null on non versionable node
     * 
     * @return self|NULL
     */
    public function getVersionAttribute(): ?Version
    {
        return $this->versions()->orderBy('major', 'desc')->orderBy('minor', 'desc')->first();
    }
    
    /**
     * Get node dependencies, these are nodes referenced in the current node
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function dependencies()
    {
        return $this->belongsToMany(Node::class, 'node_dependencies', 'node_id', 'related_node_id');
    }
    
    /**
     * Get nodes that have at least one dependency with the current node
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function referencies()
    {
        return $this->belongsToMany(Node::class, 'node_dependencies', 'related_node_id');
    }
    
    /**
     * Get the parent node that holds this current node
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Node::class, 'parent_id');
    }
    
    /**
     * Load and return an instance of an specified node type for given node object or id code
     * 
     * @param int $id
     * @param Node $node
     * @return Object
     */
    public static function instanceFromNodeType(int $id = null, Node $node = null): Node
    {
        if (! $id && ! $node) {
            throw new \UnexpectedValueException();
        }
        if ($id) {
            $node = Node::findOrFail($id);
        }
        $class = "{$node->node_type->namespace}\\{$node->type}";
        return $class::findOrFail($node->id);
    }
    
    /**
     * Save aditional data for this node in a creation or deletion operation
     * 
     * @param array $options
     */
    public function save(array $options = []): bool
    {
        DB::beginTransaction();
        $result = parent::save($options);
        if ($result and $this->isVersionableProperty) {
            
            // A new version for this node must be created
            if (Version::generate($this) === false) {
                $result = false;
            }
        }
        if ($result) {
            DB::commit();
        } else {
            DB::rollBack();
        }
        return $result;
    }
    
    /**
     * // TODO ajlucena: Try this with boot function with the deleting event
     * 
     * {@inheritDoc}
     * @see \Illuminate\Database\Eloquent\Model::delete()
     */
    public function delete()
    {
        // Remove all versions
        foreach ($this->versions as $version) {
            if ($version->delete() === false) {
                return false;
            }
        }
       return parent::delete();
    }
    
    /**
     * The "booting" method of the model
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new NodeTypeScope);
    }
    
    /**
     * Add specified properties to the node
     * 
     * @param array $properties
     */
    protected function loadProperties(array $properties = []): void
    {
        $this->nodeProperties = $properties + $this->nodeProperties;
    }
}
