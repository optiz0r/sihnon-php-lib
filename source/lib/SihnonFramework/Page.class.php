<?php

class SihnonFramework_Page {

    private $smarty;
    private $request;

    private $page;

    public function __construct(Smarty $smarty, SihnonFramework_RequestParser $request, $page = null) {
        $this->smarty = $smarty;
        $this->request = $request;
        $this->page = $page;
        
        if ($page === null) {
            $this->page = $request->page();
        }
    }

    public function page() {
        return $this->page;
    }

    public function template_filename() {
        return $this->page . '.tpl';
    }
    
    public function code_filename() {
        return $this->page . '.php';
    }

    public function evaluate($template_variables = array()) {
        $code_filename     = $this->code_filename();
        $template_filename = $this->template_filename();

        $content = '';
        try {
            $this->smarty->assign('page', $this);
            $this->smarty->assign('requested_page', $this->page);
            $content = $this->render($template_filename, $code_filename, $template_variables);
        } catch (SihnonFramework_Exception_AbortEntirePage $e) {
            return false;
        } catch (SihnonFramework_Exception_FileNotFound $e) {
            $content = $this->render('errors/404.tpl', 'errors/404.php');
        } catch (SihnonFramework_Exception_NotAuthorised $e) {
            $content = $this->render('errors/401.tpl', 'errors/404.php');
        } catch (SihnonFramework_Exception $e) {
            $content = $this->render('errors/unhandled-exception.tpl', 'errors/unhandled-exception.php', array(
                'exception' => $e,
            ));
        } 
        
        $this->smarty->assign('page_content', $content);
        
        return true;
    }
    
    protected function render($template_filename, $code_filename = null, $template_variables = array()) {
        if ( ! $this->smarty->templateExists($template_filename)) {
            throw new SihnonFramework_Exception_FileNotFound($template_filename);
        }
        
        // Copy all the template variables into the namespace for this function,
        // so that they are readily available to the template
        foreach ($template_variables as $__k => $__v) {
            $$__k = $__v;
        }
        
        // Include the template code file, which will do all the work for this page
        $real_code_filename = $this->request->template_code_dir() . DIRECTORY_SEPARATOR . $code_filename;
        if ($code_filename && file_exists($real_code_filename)) {
            include $real_code_filename;
        }
        
        // Now execute the template itself, which will render the results of the code file
        return $this->smarty->fetch($template_filename);
    }
    
    public function include_template($page, $template_variables = array()) {
        $subpage = new Sihnon_Page($this->smarty, $this->request, $page);
        return $subpage->render($subpage->template_filename(), $subpage->code_filename(), $template_variables);
    }
    
    public static function redirect($relative_url) {
        $absolute_url = SihnonFramework_Main::instance()->absoluteUrl($relative_url);
        
        header("Location: $absolute_url");
        
        throw new SihnonFramework_Exception_AbortEntirePage();
    }

};

?>
