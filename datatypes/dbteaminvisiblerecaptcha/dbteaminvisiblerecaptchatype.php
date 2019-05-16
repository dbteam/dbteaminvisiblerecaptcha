<?php



class DBTeamInvisiblereCAPTCHAType extends eZDataType
{
    const DATA_TYPE_STRING = "dbteaminvisiblerecaptcha";
    const FIELD_NAME = "data_text";
    const HTTP_FIELD_NAME_PART = "invisible_recaptcha";


    public function __construct()
    {
        parent::eZDataType(
            self::DATA_TYPE_STRING,
            ezpI18n::tr(
                $this->getTranslationContext(),
                "DB-Team Invisible reCAPTCHA"
            )
        );
    }

    
    public function getTranslationContext()
    {

    	return DBTeamInvisiblereCAPTCHAInfo::TRANSLATIONS_DEFAULT_CONTEXT;
    }

    /**
     * @param eZContentObjectAttribute $contentObjectAttribute
     * @param $currentVersion
     * @param eZContentObjectAttribute $originalContentObjectAttribute
     */
    function initializeObjectAttribute($contentObjectAttribute, $currentVersion, $originalContentObjectAttribute)
    {
        if ( $currentVersion != false )
        {
            $dataText = $originalContentObjectAttribute->attribute( "data_text" );
            $contentObjectAttribute->setAttribute( "data_text", $dataText );
        }
    }

    /**
     * @param eZContentObjectAttribute $contentObjectAttribute
     * @param string $POSTParamValue - content attribute filed value, should be: "", "0" because is invisible for user
     *
     * @return int
     */
    protected function validateCAPTCHAHTTPInput($contentObjectAttribute, $POSTParamValue)
    {
        /** @var eZContentClassAttribute $contetnClassAttr */
        $contetnClassAttr = $contentObjectAttribute->contentClassAttribute();
        if(!$contetnClassAttr->attribute('is_information_collector'))
        {

            return eZInputValidator::STATE_ACCEPTED;
        }

        /*
        Not sure, what to do in not required case?
        */
        if(!$contetnClassAttr->attribute("is_required"))
        {
            if($POSTParamValue)//"". "0", 0
            {
                //return eZInputValidator::STATE_INVALID;// is it good idea?
            }
        }
        $userId = eZUser::currentUserID();
        if($userId != eZUser::anonymousId()) {
            return eZInputValidator::STATE_ACCEPTED;
        }

        //if(stripos($_SERVER['REQUEST_URI'], '/content/edit/') !== false) {
        //return eZInputValidator::STATE_ACCEPTED;
        //}

        $ezCaptchaService = new \DBTeam\Google\InvisibleReCaptcha\EzInvisibleReCaptchaService();
        if($ezCaptchaService->verifyCaptcha() and !$POSTParamValue)
        {
            return eZInputValidator::STATE_ACCEPTED;
        }
        $contentObjectAttribute->setValidationError(
            ezpI18n::tr($this->getTranslationContext(), 'You are a BOT!')
        );

        return eZInputValidator::STATE_INVALID;
    }

    /**
     * @param eZHTTPTool $http
     * @param string $base
     * @param eZContentObjectAttribute $contentObjectAttribute
     *
     * @return int
     */
    function validateObjectAttributeHTTPInput($http, $base, $contentObjectAttribute)
    {
        $POSTVarName = $this->getPOSTVarName($base, $contentObjectAttribute);
        $inputValue = $http->postVariable($POSTVarName);

        return $this->validateCAPTCHAHTTPInput($contentObjectAttribute, $inputValue);
    }

    /**
     * @param string $base
     * @param eZContentObjectAttribute $contentObjectAttribute
     *
     * @return string
     */
    public function getPOSTVarName($base, eZContentObjectAttribute $contentObjectAttribute)
    {

    	return $base . '_' . self::HTTP_FIELD_NAME_PART . '_' . $contentObjectAttribute->attribute('id');
    }

    public function getFieldName()
    {

    	return self::FIELD_NAME;
    }

    /**
     * @param eZHTTPTool $http
     * @param string $base
     * @param eZContentObjectAttribute $contentObjectAttribute
     *
     * @return bool
     */
    function fetchObjectAttributeHTTPInput($http, $base, $contentObjectAttribute)
    {
        $varName = $this->getPOSTVarName($base, $contentObjectAttribute);
        if($http->hasPostVariable($varName))
        {
            $data = $http->postVariable($varName);
            $contentObjectAttribute->setAttribute(self::FIELD_NAME, $data);

            return true;
        }

        return true;
    }

    function storeObjectAttribute($contentObjectAttribute)
    {
        return;
    }

    /**
     * @param eZContentObjectAttribute $contentObjectAttribute
     *
     * @return string
     */
    function objectAttributeContent( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute(self::FIELD_NAME);
    }


    function validateCollectionAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        return $this->validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute );
    }

    /**
     * @param $collection
     * @param eZInformationCollectionAttribute $collectionAttribute
     * @param eZHTTPTool $http
     * @param string $base
     * @param eZContentObjectAttribute $contentObjectAttribute
     *
     * @return bool
     */
    function fetchCollectionAttributeHTTPInput($collection, $collectionAttribute, $http, $base, $contentObjectAttribute)
    {
        $varName = $this->getPOSTVarName($base, $contentObjectAttribute);
        if($http->hasPostVariable($varName))
        {
            $dataText = $http->postVariable($varName);
            $collectionAttribute->setAttribute(self::FIELD_NAME, $dataText);

            return true;
        }
        return false;
    }



    function isIndexable()
    {
        return false;
    }


    function metaData( $contentObjectAttribute )
    {
        return '';
    }

    /**
     * @param eZContentObjectAttribute $contentObjectAttribute
     *
     * @return string
     */
    function toString($contentObjectAttribute)
    {
        return $contentObjectAttribute->attribute(self::FIELD_NAME);
    }

    /**
     * @param eZContentObjectAttribute $contentObjectAttribute
     * @param string $string
     *
     * @return void
     */
    function fromString($contentObjectAttribute, $string)
    {
        $contentObjectAttribute->setAttribute(self::FIELD_NAME, $string);
    }

    /**
     * @param eZContentObjectAttribute $contentObjectAttribute
     * @param null|string $name
     *
     * @return string
     */
    function title($contentObjectAttribute, $name = null)
    {
        return $contentObjectAttribute->attribute(self::FIELD_NAME);
    }

    /**
     * @param eZContentObjectAttribute $contentObjectAttribute
     *
     * @return bool
     */
    function hasObjectAttributeContent($contentObjectAttribute)
    {
        return trim($contentObjectAttribute->attribute(self::FIELD_NAME)) != '';
    }


    function isInformationCollector()
    {
        return true;
    }


    function sortKey($contentObjectAttribute)
    {
        return '';
    }


    function sortKeyType()
    {
        return 'string';
    }
}

eZDataType::register(
    DBTeamInvisiblereCAPTCHAType::DATA_TYPE_STRING,
    "DBTeamInvisiblereCAPTCHAType"
);

