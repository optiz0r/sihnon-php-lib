<?php

class SihnonFramework_Utility_DirectoryFilter extends FilterIterator {
    public function accept() {
        return is_dir($this->current()->getFilename());
    }
}

?>