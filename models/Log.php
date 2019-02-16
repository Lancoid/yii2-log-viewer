<?php

namespace lancoid\yii2LogViewer\models;

use yii\helpers\FileHelper;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\base\BaseObject;
use Yii;

/**
 * Class Log
 *
 * @property string       $name
 * @property string       $alias
 * @property null|string  $stamp
 * @property string       $slug
 * @property string       $fileName
 * @property boolean      $isExist
 * @property boolean      $isZip
 * @property integer|null $size
 * @property integer|null $updatedAt
 * @property string       $downloadName
 * @property array        $counts
 */
class Log extends BaseObject
{
    /**
     * @var string
     */
    private $_name;

    /**
     * @var string
     */
    private $_alias;

    /**
     * @var string|null
     */
    private $_stamp;

    /**
     * @var string
     */
    private $_fileName;

    /**
     * @param string      $name
     * @param string      $alias
     * @param null|string $stamp
     * @param array       $config
     */
    public function __construct($name, $alias, $stamp = null, $config = [])
    {
        $this->_name = $name;
        $this->_alias = $alias;
        $this->_stamp = $stamp;
        $this->_fileName = static::extractFileName($alias, $stamp);
        parent::__construct($config);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->_alias;
    }

    /**
     * @return string
     */
    public function getStamp()
    {
        return $this->_stamp;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return static::extractSlug($this->_name);
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->_fileName;
    }

    /**
     * @return boolean
     */
    public function getIsExist()
    {
        return file_exists($this->getFileName());
    }

    /**
     * @return bool
     */
    public function getIsZip()
    {
        return $this->getIsExist() ? StringHelper::endsWith($this->getFileName(), '.zip') : false;
    }

    /**
     * @return integer|null
     */
    public function getSize()
    {
        return $this->getIsExist() ? filesize($this->getFileName()) : null;
    }

    /**
     * @return integer|null
     */
    public function getUpdatedAt()
    {
        return $this->getIsExist() ? filemtime($this->getFileName()) : null;
    }

    /**
     * @return string
     */
    public function getDownloadName()
    {
        return $this->getSlug() . '.log';
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public static function extractSlug($name)
    {
        return Inflector::slug($name);
    }

    /**
     * @param string      $alias
     * @param null|string $stamp
     *
     * @return string
     */
    public static function extractFileName($alias, $stamp = null)
    {
        $fileName = FileHelper::normalizePath(Yii::getAlias($alias, false));

        return $stamp === null ? $fileName : $fileName . '.' . $stamp;
    }

    /**
     * @param string $alias
     * @param string $fileName
     *
     * @return string|null
     */
    public static function extractFileStamp($alias, $fileName)
    {
        $originName = FileHelper::normalizePath(Yii::getAlias($alias, false));

        return strpos($fileName, $originName) === 0 ? substr($fileName, strlen($originName) + 1) : null;
    }
}
