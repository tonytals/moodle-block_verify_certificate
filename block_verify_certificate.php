<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

// Updated for use with Certificate v3+ Chardelle Busch.

class block_verify_certificate extends block_base {

    function init() {
        $this->title = get_string('title', 'block_verify_certificate');
    }

    function applicable_formats() {
        return array('all' => true);
    }
    function get_content() {

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass;
        //$this->content->text = '<p>'.get_string('entercode', 'certificate').'</p>';
        $url = new moodle_url('/blocks/verify_certificate/index.php');
        $this->content->text .= '<center><form class="loginform" name="cert" method="post" action="'. $url . '">';
        $this->content->text .= 'Por nome <input type="radio" name="por" id=name="porNome" value="nome" checked />'; // Para pesquisa por nome
        $this->content->text .= 'Por código <input type="radio" name="por" id=name="porCodigo" value="code"/>'; // Para pesquisa por codigo
        $this->content->text .= '<input type="text" name="certnumber" id=name="certnumber" size="10" value="" />';
        $this->content->text .= '<input type="submit" value="Verificar"/></form>';
        $this->content->text .= '<center>';
        $this->content->footer = '';
   
        /**************** ANTIGO FORMULÁRIO ********************
      
        $this->content->text .= '<center><form class="loginform" name="cert" method="post" action="'. $url . '">';
        $this->content->text .= '<input type="text" name="certnumber" id=name="certnumber" size="15" value="" />';
        //$this->content->text .= '<input type="submit" value="'.get_string('validate', 'certificate').'"/></form>';
        $this->content->text .= '<input type="submit" value="Verificar"/></form>';
        $this->content->text .= '<center>';
        $this->content->footer = '';
      
        */

        return $this->content;
    }
    function instance_allow_config() {
        return false;
    }

}
