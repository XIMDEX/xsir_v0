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
        'type'
    ];

    /**
     * Set bassic properties to the node
     *
     * @var array
     */
    protected $nodeProperties = [
        'icon' => null,
        'isHidden' => false,
        'isPublishable' => false,
    ];

    protected $_relations = [
        'node_type' => [
            'type' => 'belongsTo',
            'model' => NodeType::class
        ],
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->attributes = array_merge($this->attributes, [
            'type' => class_basename(static::class)
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
        return $this->nodeProperties;
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
        return $this->node_type;
    }
}
