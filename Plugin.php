<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * FancyBox 适用于Typecho1.1的图片灯箱
 * 
 * @package FancyBox
 * @author 阳光加冰
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

        $custom_parse = new Typecho_Widget_Helper_Form_Element_Textarea(
            'custom_parse', NULL, NULL, _t("解析过滤"), _t("默认解析所有img标签， 若希望某些img不进入解析范围， 便在一行中输入不要解析的选择器。 例如： #avatar, .smile")
        );
        $form->addInput($custom_parse);

        $index_load = new Typecho_Widget_Helper_Form_Element_Radio(
            'index_load', array('0'=>_t('否'), '1'=>_t('是')), 0, _t('是否在主页中启用FancyBox?'), _t('默认不在主页启用灯箱效果'));
        $form->addInput($index_load);
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
        $settings = Helper::options()->plugin('FancyBox');
        /** 如果是单篇文章或者独立页面 */
        if($settings->index_load || (isset($archive->request->cid) || isset($archive->request->slug))) {
            echo '<link rel="stylesheet" type="text/css" href="' . Helper::options()->pluginUrl. '/FancyBox/css/jquery.fancybox.min.css" />';
        }
    }
    public static function footer(Widget_Archive $archive) {
        $settings = Helper::options()->plugin('FancyBox');
        
        /** 如果是单篇文章或者独立页面 */
        if($settings->index_load || (isset($archive->request->cid) || isset($archive->request->slug))) {
            /** 加载外部jquery */
            if($settings->jq_set == 1) {
                echo '<script type="text/javascript" src="https://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>';
            }
            /** 则加载灯箱js文件 */
            echo '<script type="text/javascript" src="' . Helper::options()->pluginUrl. '/FancyBox/js/jquery.fancybox.min.js"> </script>';
            $js_head = '
            <script>
            $(document).ready(function(){
                $("img").each(function(){
                    $(this).wrap(function(){';
            $custom_parse = trim(Typecho_Common::stripTags($settings->custom_parse));
            $lines = array_filter(preg_split("/(\r|\n|\r\n)/", $custom_parse));
            if(isset($lines)) {
                foreach($lines as $line) {
                    $js_head = $js_head.'if($(this).is("'.$line.'"))'."{ return ''; }";
                }
            }
            $js_end = 'return '."'<a data-fancybox=\"gallery\" data-type=\"image\" href=\"' + $(this).attr(\"src\") + '\" class=\"light-link\"></a>';".
            '       })
                })
             })
             </script>';
            echo $js_head.$js_end;
            //echo '<script type="text/javascript" src="' . Helper::options()->pluginUrl. '/FancyBox/js/lightbox.js"> </script>';
        }
    }

    
}
