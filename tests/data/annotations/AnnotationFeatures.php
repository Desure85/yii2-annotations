<?php

namespace yii\annotations\tests\data\annotations;

/**
 * Class AnnotationFeatures.
 *
 * @Annotation
 * @Target({"ALL"})
 */
class AnnotationFeatures
{
    /**
     * @var bool
     */
    public $check;

    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $integer;

    /**
     * @var bool
     */
    private $boolean;

    /**
     * @var string
     */
    private $string;

    /**
     * @var string
     */
    protected $const;

    /**
     * @var array
     */
    protected $array;

    /**
     * AnnotationFeatures constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->id = $values['id'] ?? null;
        $this->integer = $values['integer'] ?? null;
        $this->boolean = $values['boolean'] ?? null;
        $this->string = $values['string'] ?? null;
        $this->array = $values['array'] ?? null;
        $this->const = $values['const'] ?? null;
    }

    /**
     * @return bool|null
     */
    public function isCheck(): ?bool
    {
        return $this->check;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getInteger(): ?int
    {
        return $this->integer;
    }

    /**
     * @return bool
     */
    public function isBoolean(): ?bool
    {
        return $this->boolean;
    }

    /**
     * @return string|null
     */
    public function getString(): ?string
    {
        return $this->string;
    }

    /**
     * @return string|null
     */
    public function getConst(): ?string
    {
        return $this->const;
    }

    /**
     * @return array|null
     */
    public function getArray(): ?array
    {
        return $this->array;
    }
}
