<?php

class NgIntegerType extends eZDataType
{
    const DATA_TYPE_STRING = 'nginteger';

    const FIRST_NUMBER_MIN_VARIABLE = '_nginteger_first_number_min_';
    const FIRST_NUMBER_MIN = 'data_int1';

    const FIRST_NUMBER_MAX_VARIABLE = '_nginteger_first_number_max_';
    const FIRST_NUMBER_MAX = 'data_int2';

    const SECOND_NUMBER_MIN_VARIABLE = '_nginteger_second_number_min_';
    const SECOND_NUMBER_MIN = 'data_int3';

    const SECOND_NUMBER_MAX_VARIABLE = '_nginteger_second_number_max_';
    const SECOND_NUMBER_MAX = 'data_int4';

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct( self::DATA_TYPE_STRING, 'Netgen Integer' );
    }

    /**
     * Initializes the content class attribute
     *
     * @param eZContentClassAttribute $classAttribute
     */
    public function initializeClassAttribute( $classAttribute )
    {
        if ( $classAttribute->attribute( self::FIRST_NUMBER_MIN ) === null )
            $classAttribute->setAttribute( self::FIRST_NUMBER_MIN, 0 );

        if ( $classAttribute->attribute( self::FIRST_NUMBER_MAX ) === null )
            $classAttribute->setAttribute( self::FIRST_NUMBER_MAX, -1 );

        if ( $classAttribute->attribute( self::SECOND_NUMBER_MIN ) === null )
            $classAttribute->setAttribute( self::SECOND_NUMBER_MIN, 0 );

        if ( $classAttribute->attribute( self::SECOND_NUMBER_MAX ) === null )
            $classAttribute->setAttribute( self::SECOND_NUMBER_MAX, -1 );

        $classAttribute->store();
    }

    /**
     * Initializes content object attribute based on another attribute
     *
     * @param eZContentObjectAttribute $contentObjectAttribute
     * @param eZContentObjectVersion $currentVersion
     * @param eZContentObjectAttribute $originalContentObjectAttribute
     */
    public function initializeObjectAttribute( $contentObjectAttribute, $currentVersion, $originalContentObjectAttribute )
    {
        if ( $currentVersion != false )
        {
            $originalContent = $originalContentObjectAttribute->attribute( 'content' );
            $contentObjectAttribute->setContent( $originalContent );
            $contentObjectAttribute->store();
        }
    }

    /**
     * Validates the input and returns true if the input was valid for this datatype
     *
     * @param eZHTTPTool $http
     * @param string $base
     * @param eZContentObjectAttribute $contentObjectAttribute
     *
     * @return bool
     */
    public function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $contentObjectAttributeID = $contentObjectAttribute->attribute( 'id' );

        $firstNumber = trim( $http->postVariable( $base . '_nginteger_first_number_' . $contentObjectAttributeID, '' ) );
        $secondNumber = trim( $http->postVariable( $base . '_nginteger_second_number_' . $contentObjectAttributeID, '' ) );

        if ( $contentObjectAttribute->validateIsRequired() )
        {
            if ( strlen( $firstNumber ) == 0 || strlen( $secondNumber ) == 0 )
            {
                $contentObjectAttribute->setValidationError( 'Input is required.' );
                return eZInputValidator::STATE_INVALID;
            }
        }

        $classAttribute = $contentObjectAttribute->contentClassAttribute();

        if ( ( strlen( $firstNumber ) > 0 && !is_numeric( $firstNumber ) ) || ( strlen( $secondNumber ) > 0 && !is_numeric( $secondNumber ) ) )
        {
            $contentObjectAttribute->setValidationError( 'Input is required.' );
            return eZInputValidator::STATE_INVALID;
        }

        if ( strlen( $firstNumber ) > 0 )
        {
            if ( is_numeric( $classAttribute->attribute( 'data_int1' ) ) && $firstNumber < $classAttribute->attribute( 'data_int1' ) )
            {
                $contentObjectAttribute->setValidationError( "First number must not be lower than {$classAttribute->attribute( 'data_int1' )}." );
                return eZInputValidator::STATE_INVALID;
            }

            if ( is_numeric( $classAttribute->attribute( 'data_int2' ) ) && $firstNumber > $classAttribute->attribute( 'data_int2' ) )
            {
                $contentObjectAttribute->setValidationError( "First number must not be higher than {$classAttribute->attribute( 'data_int2' )}." );
                return eZInputValidator::STATE_INVALID;
            }
        }

        if ( strlen( $secondNumber ) > 0 )
        {
            if ( is_numeric( $classAttribute->attribute( 'data_int3' ) ) && $secondNumber < $classAttribute->attribute( 'data_int3' ) )
            {
                $contentObjectAttribute->setValidationError( "Second number must not be lower than {$classAttribute->attribute( 'data_int3' )}." );
                return eZInputValidator::STATE_INVALID;
            }

            if ( is_numeric( $classAttribute->attribute( 'data_int4' ) ) && $secondNumber > $classAttribute->attribute( 'data_int4' ) )
            {
                $contentObjectAttribute->setValidationError( "Second number must not be higher than {$classAttribute->attribute( 'data_int4' )}." );
                return eZInputValidator::STATE_INVALID;
            }
        }

        return eZInputValidator::STATE_ACCEPTED;
    }

    /**
     * Fetches the HTTP POST input and stores it in the data instance
     *
     * @param eZHTTPTool $http
     * @param string $base
     * @param eZContentObjectAttribute $contentObjectAttribute
     *
     * @return bool
     */
    public function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $contentObjectAttributeID = $contentObjectAttribute->attribute( 'id' );

        if ( !$http->hasPostVariable( $base . '_nginteger_first_number_' . $contentObjectAttributeID ) )
            return false;

        if ( !$http->hasPostVariable( $base . '_nginteger_second_number_' . $contentObjectAttributeID ) )
            return false;

        $firstNumber = trim( $http->postVariable( $base . '_nginteger_first_number_' . $contentObjectAttributeID ) );
        $secondNumber = trim( $http->postVariable( $base . '_nginteger_second_number_' . $contentObjectAttributeID ) );

        $contentObjectAttribute->setContent(
            array(
                'first_number' => (int) $firstNumber,
                'second_number' => (int) $secondNumber
            )
        );

        return true;
    }

    /**
     * Stores the object attribute
     *
     * @param eZContentObjectAttribute $attribute
     */
    public function storeObjectAttribute( $attribute )
    {
        if ( !( $attribute->attribute( 'id' ) > 0 ) )
            return;

        $attributeContent = $attribute->attribute( 'content' );
        if ( !is_array( $attributeContent ) )
            return;

        $db = eZDB::instance();

        $queryResult = $db->arrayQuery(
            "SELECT
                COUNT( * ) AS count
            FROM nginteger
            WHERE
                contentobject_attribute_id = {$attribute->attribute( 'id' )} AND
                version = {$attribute->attribute( 'version' )}"
        );

        $hasData = (int) $queryResult[0]['count'] > 0;

        if ( $hasData )
        {
            $db->query(
                "UPDATE nginteger
                SET
                    first_number = " . $attributeContent['first_number'] . "," .
                    "second_number = " . $attributeContent['second_number'] .
                " WHERE
                    contentobject_attribute_id = {$attribute->attribute( 'id' )} AND
                    version = {$attribute->attribute( 'version' )}"
            );
        }
        else
        {
            $db->query(
                "INSERT INTO nginteger(
                    contentobject_attribute_id,
                    version,
                    first_number,
                    second_number
                ) VALUES (
                    {$attribute->attribute( 'id' )},
                    {$attribute->attribute( 'version' )}," .
                    $attributeContent['first_number'] . "," .
                    $attributeContent['second_number'] .
                ")"
            );
        }
    }

    /**
     * Validates class attribute HTTP input
     *
     * @param eZHTTPTool $http
     * @param string $base
     * @param eZContentClassAttribute $attribute
     *
     * @return bool
     */
    public function validateClassAttributeHTTPInput( $http, $base, $attribute )
    {
        $classAttributeID = $attribute->attribute( 'id' );

        $firstNumberMin = trim( $http->postVariable( $base . self::FIRST_NUMBER_MIN_VARIABLE . $classAttributeID, '' ) );
        $firstNumberMax = trim( $http->postVariable( $base . self::FIRST_NUMBER_MAX_VARIABLE . $classAttributeID, '' ) );
        $secondNumberMin = trim( $http->postVariable( $base . self::SECOND_NUMBER_MIN_VARIABLE . $classAttributeID, '' ) );
        $secondNumberMax = trim( $http->postVariable( $base . self::SECOND_NUMBER_MAX_VARIABLE . $classAttributeID, '' ) );

        if ( strlen( $firstNumberMin ) > 0 && !is_numeric( $firstNumberMin ) )
            return eZInputValidator::STATE_INVALID;

        if ( strlen( $firstNumberMax ) > 0 && !is_numeric( $firstNumberMax ) )
            return eZInputValidator::STATE_INVALID;

        if ( strlen( $secondNumberMin ) > 0 && !is_numeric( $secondNumberMin ) )
            return eZInputValidator::STATE_INVALID;

        if ( strlen( $secondNumberMax ) > 0 && !is_numeric( $secondNumberMax ) )
            return eZInputValidator::STATE_INVALID;

        if ( is_numeric( $firstNumberMin ) && is_numeric( $firstNumberMax ) && $firstNumberMin > $firstNumberMax )
            return eZInputValidator::STATE_INVALID;

        if ( is_numeric( $secondNumberMin ) && is_numeric( $secondNumberMax ) && $secondNumberMin > $secondNumberMax )
            return eZInputValidator::STATE_INVALID;

        return eZInputValidator::STATE_ACCEPTED;
    }

    /**
     * Fetches class attribute HTTP input and stores it
     *
     * @param eZHTTPTool $http
     * @param string $base
     * @param eZContentClassAttribute $attribute
     *
     * @return bool
     */
    public function fetchClassAttributeHTTPInput( $http, $base, $attribute )
    {
        $classAttributeID = $attribute->attribute( 'id' );

        $firstNumberMin = trim( $http->postVariable( $base . self::FIRST_NUMBER_MIN_VARIABLE . $classAttributeID, '' ) );
        $firstNumberMax = trim( $http->postVariable( $base . self::FIRST_NUMBER_MAX_VARIABLE . $classAttributeID, '' ) );
        $secondNumberMin = trim( $http->postVariable( $base . self::SECOND_NUMBER_MIN_VARIABLE . $classAttributeID, '' ) );
        $secondNumberMax = trim( $http->postVariable( $base . self::SECOND_NUMBER_MAX_VARIABLE . $classAttributeID, '' ) );

        if ( $http->hasPostVariable( $base . self::FIRST_NUMBER_MIN_VARIABLE . $classAttributeID ) )
            $attribute->setAttribute( self::FIRST_NUMBER_MIN, is_numeric( $firstNumberMin ) ? (int) $firstNumberMin : -1 );

        if ( $http->hasPostVariable( $base . self::FIRST_NUMBER_MAX_VARIABLE . $classAttributeID ) )
            $attribute->setAttribute( self::FIRST_NUMBER_MAX, is_numeric( $firstNumberMax ) ? (int) $firstNumberMax : -1 );

        if ( $http->hasPostVariable( $base . self::SECOND_NUMBER_MIN_VARIABLE . $classAttributeID ) )
            $attribute->setAttribute( self::SECOND_NUMBER_MIN, is_numeric( $secondNumberMin ) ? (int) $secondNumberMin : -1 );

        if ( $http->hasPostVariable( $base . self::SECOND_NUMBER_MAX_VARIABLE . $classAttributeID ) )
            $attribute->setAttribute( self::SECOND_NUMBER_MAX, is_numeric( $secondNumberMax ) ? (int) $secondNumberMax : -1 );

        return true;
    }

    /**
     * Returns the content
     *
     * @param eZContentObjectAttribute $attribute
     *
     * @return array
     */
    public function objectAttributeContent( $attribute )
    {
        $db = eZDB::instance();

        $queryResult = $db->arrayQuery(
            "SELECT
                COUNT( * ) AS count
            FROM nginteger
            WHERE
                contentobject_attribute_id = {$attribute->attribute( 'id' )} AND
                version = {$attribute->attribute( 'version' )}"
        );

        $hasData = (int) $queryResult[0]['count'] > 0;

        if ( !$hasData )
            return false;

        $queryResult = $db->arrayQuery(
            "SELECT
                first_number,
                second_number
            FROM nginteger
            WHERE
                contentobject_attribute_id = {$attribute->attribute( 'id' )} AND
                version = {$attribute->attribute( 'version' )}"
        );

        return array(
            'first_number' => (int) $queryResult[0]['first_number'],
            'second_number' => (int) $queryResult[0]['second_number']
        );
    }

    /**
     * Returns the meta data used for storing search indices
     *
     * @param eZContentObjectAttribute $attribute
     *
     * @return string
     */
    public function metaData( $attribute )
    {
        $attributeContent = $attribute->attribute( 'content' );
        if ( !is_array( $attributeContent ) )
            return '';

        return $attributeContent['first_number'] . ',' . $attributeContent['second_number'];
    }

    /**
     * Delete stored object attribute
     *
     * @param eZContentObjectAttribute $contentObjectAttribute
     * @param eZContentObjectVersion $version
     */
    public function deleteStoredObjectAttribute( $contentObjectAttribute, $version = null )
    {
        $db = eZDB::instance();

        if ( $version != null )
        {
            $db->query(
                "DELETE FROM nginteger
                WHERE
                    contentobject_attribute_id = {$contentObjectAttribute->attribute( 'id' )} AND
                    version = {$contentObjectAttribute->attribute( 'version' )}"
            );
        }
        else
        {
            $db->query(
                "DELETE FROM nginteger
                WHERE
                    contentobject_attribute_id = {$contentObjectAttribute->attribute( 'id' )}"
            );
        }
    }

    /**
     * Returns the content of nginteger attribute for use as a title
     *
     * @param eZContentObjectAttribute $attribute
     * @param string $name
     *
     * @return string
     */
    public function title( $attribute, $name = null )
    {
        return $this->metaData( $attribute );
    }

    /**
     * Returns true if content object attribute has content
     *
     * @param eZContentObjectAttribute $contentObjectAttribute
     *
     * @return bool
     */
    public function hasObjectAttributeContent( $contentObjectAttribute )
    {
        $db = eZDB::instance();

        $queryResult = $db->arrayQuery(
            "SELECT
                COUNT( * ) AS count
            FROM nginteger
            WHERE
                contentobject_attribute_id = {$contentObjectAttribute->attribute( 'id' )} AND
                version = {$contentObjectAttribute->attribute( 'version' )}"
        );

        return (int) $queryResult[0]['count'] > 0;
    }

    /**
     * Returns if the content is indexable
     *
     * @return bool
     */
    public function isIndexable()
    {
        return false;
    }

    /**
     * Returns string representation of a content object attribute
     *
     * @param eZContentObjectAttribute $contentObjectAttribute
     *
     * @return string
     */
    public function toString( $contentObjectAttribute )
    {
        return $this->metaData( $contentObjectAttribute );
    }

    /**
     * Creates the content object attribute content from the input string
     *
     * @param eZContentObjectAttribute $contentObjectAttribute
     * @param string $string
     *
     * @return bool
     */
    public function fromString( $contentObjectAttribute, $string )
    {
        $stringArray = explode( ',', $string );
        if ( is_array( $stringArray ) && count( $stringArray ) == 2 )
        {
            $contentObjectAttribute->setContent(
                array(
                    'first_number' => (int) $stringArray[0],
                    'second_number' => (int) $stringArray[1]
                )
            );
        }
    }

    /**
     * Returns if the content supports batch initialization
     *
     * @return bool
     */
    public function supportsBatchInitializeObjectAttribute()
    {
        return true;
    }

    /**
     * Sets grouped_input to true for edit view of the datatype
     *
     * @param eZContentObjectAttribute $objectAttribute
     * @param array|bool $mergeInfo
     *
     * @return array
     */
    public function objectDisplayInformation( $objectAttribute, $mergeInfo = false )
    {
        return eZDataType::objectDisplayInformation(
            $objectAttribute,
            array( 'edit' => array( 'grouped_input' => true ) )
        );
    }
}

eZDataType::register( NgIntegerType::DATA_TYPE_STRING, 'NgIntegerType' );

?>
