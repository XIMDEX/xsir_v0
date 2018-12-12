<?php

namespace Ximdex\Models;

use Ximdex\Scopes\NodeTypeScope;
use Ximdex\Core\Database\Eloquent\Model;

class Node extends Model
{
    protected $table = 'nodes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent',
        'name',
    ];

    /*
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
        'last_version'
    ];

    /**
     * Set bassic properties to the node
     *
     * @var array
     */
    protected $nodeProperties = [
        'icon' => "nodes",
        'isHidden' => true,
        'isPublishable' => false,
        'isCacheable' => true
    ];

    protected $_relations = [
        'node_type' => [
            'type' => 'belongsTo',
            'model' => NodeType::class
        ],
        'version' => [
            'type' => 'hasMany',
            'model' => Version::class,
            'foreignKey' => 'node_id',
            'localKey' => 'id',
        ]
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->attributes = array_merge($this->attributes, [
            'node_type_id' => NodeType::where('type', class_basename(static::class))->select('id')->first()->id
        ]);
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new NodeTypeScope);
    }

    public function getPropertiesAttribute() : array
    {
        $class = "{$this->node_type->namespace}\\{$this->type}";
        $result = (new $class)->nodeProperties;
        $result['icon'] = config("xsir.icons.{$result['icon']}", $result['icon']);
        return $result;
    }

    public function getIconPropertyAttribute()
    {
        return $this->properties['icon'];
    }

    public function getIsHiddenPropertyAttribute()
    {
        return $this->properties['isHidden'];
    }

    public function getTypeAttribute()
    {
        return $this->node_type->type;
    }

    public function getLastVersionAttribute()
    {
        return $this->version()->orderBy('major', 'asc')->first();
    }
}
