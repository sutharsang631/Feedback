<?php
namespace Custom\ConfigViewer\Api\Data;

interface GridInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case.
     */
    const ENTITY_ID = 'entity_id';
    const PARENT_KEY = 'parent_key';
    const KEY = 'key';
    const VALUE = 'value';

    /**
     * Get EntityId.
     *
     * @return int
     */
    public function getEntityId();

    /**
     * Set EntityId.
     *
     * @param int $entityId
     */
    public function setEntityId($entityId);

    /**
     * Get Parent Key.
     *
     * @return string
     */
    public function getParentKey();

    /**
     * Set Parent Key.
     *
     * @param string $parentKey
     */
    public function setParentKey($parentKey);

    /**
     * Get Key.
     *
     * @return string
     */
    public function getKey();

    /**
     * Set Key.
     *
     * @param string $key
     */
    public function setKey($key);

    /**
     * Get Value.
     *
     * @return string
     */
    public function getValue();

    /**
     * Set Value.
     *
     * @param string $value
     */
    public function setValue($value);
}
