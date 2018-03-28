<?php

/**
 * @package   yii2-markdown
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2015
 * @version   1.3.1
 */

namespace kartik\markdown;

use Yii;

/**
 * A Markdown conversion and editing module for Yii Framework 2.0
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class Module extends \kartik\base\Module
{
    const MODULE = 'markdown';

    /**
     * @var string the controller action route used
     * for markdown editor preview
     */
    public $previewAction = '/markdown/parse/preview';

    /**
     * @var string the controller action route used
     * for downloading the markdown exported file
     */
    public $downloadAction = '/markdown/parse/download';

    /**
     * @var array the list of custom conversion patterns
     * - array key is the pattern to search
     * - array value is the pattern to replace
     */
    public $customConversion = [
        '<table>' => '<table class="table table-bordered table-striped">'
    ];
    
    /**
     * @var boolean|callable turn smarty temlates on/off. Can be a callable function with the $module passed as a parameter.
     */
    public $smarty = false;
    
    /**
     * @var array|null Smarty class configuration params
     */
    public $smartyParams;
    
    /**
     * @var boolean wether to add Yii::$app as a variable for Smarty templates.
     */
    public $smartyYiiApp = false;
    
    /**
     * @var boolean whether to add Yii::$app->params as smarty configs accessable via {#paramName#} in the editor
     */
    public $smartyYiiParams = false;

    /**
     * @var boolean whether to use PHP SmartyPants
     * to process the markdown output.
     */
    public $smartyPants = true;

    public function init()
    {
        $this->_msgCat = 'kvmarkdown';
        parent::init();
    }

}
