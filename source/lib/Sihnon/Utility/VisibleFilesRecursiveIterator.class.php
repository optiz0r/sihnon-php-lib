<?php

class Sihnon_Utility_VisibleFilesRecursiveIterator extends RecursiveFilterIterator {
    public function accept() {
        return !(substr($this->current()->getFilename(), 0, 1) == '.');
    }
}

?>