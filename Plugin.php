<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * FancyBox 适用于Typecho1.1的图片灯箱
 * 
 * @package FancyBox
 * @author sunshineAddIce
 * @version 1.0.0
 * @link https://lose7.org
 */
class FancyBox_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        Typecho_Plugin::factory('Widget_Archive')->header = array(__CLASS__, 'header');
        Typecho_Plugin::factory('Widget_Archive')->footer = array(__CLASS__, 'footer');
    }
    
    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){}
    
    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {
        $jq_set = new Typecho_Widget_Helper_Form_Element_Radio(
            'jq_set', array('0'=> _t('自己处理'), '1'=> _t('随着本插件载入')), 1, _t('jQuery 来源'), _t('本插件缺省从http://libs.baidu.com/jquery/2.0.0/jquery.min.js加载'));
        $form->addInput($jq_set);
    }

    
    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}
    


    /**
     * 插件实现方法
     * 
     * @access public
     * @return void
     */
    public static function render()
    {
    }

    public static function header($header, Widget_Archive $archive) {
        /** 如果是单篇文章或者独立页面 */
        if(isset($archive->request->cid)) {
            /** 则加载灯箱css文件 */
            echo '<link rel="stylesheet" type="text/css" href="' . Helper::options()->pluginUrl. '/FancyBox/css/jquery.fancybox.min.css" />';
        }
    }
    public static function footer(Widget_Archive $archive) {
        /** 如果是单篇文章或者独立页面 */
        if(isset($archive->request->cid)) {
            /** 加载外部jquery */
            if(Helper::options()->plugin('FancyBox')->jq_set == 1) {
                echo '<script type="text/javascript" src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>';
            }
            /** 则加载灯箱js文件 */
            echo '<script type="text/javascript" src="' . Helper::options()->pluginUrl. '/FancyBox/js/jquery.fancybox.min.js"> </script>';
            echo '<script type="text/javascript" src="' . Helper::options()->pluginUrl. '/FancyBox/js/lightbox.js"> </script>';
        }
    }

    
}
