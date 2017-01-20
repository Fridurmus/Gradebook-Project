<?php
/**
 * Created by PhpStorm.
 * User: Sean Davis
 * Date: 1/16/2017
 * Time: 2:35 PM
 */

function textField($label, $idname, $phtext, $value='')
{
    return <<<EOD
            <div class="form-group">
            <label for="$idname">$label</label>
            <input type="text" class="form-control" id="$idname" name="$idname", placeholder="$phtext" value="$value" required> 
            </input>
            </div>
EOD;
}

function numField($label, $idname, $max='', $min='', $value=''){
    return <<<EOD
            <div class="form-group">
            <label for="$idname">$label</label>
            <input type="number" class="form-control" id="$idname" name="$idname" value="$value" max="$max" min="$min" step="any" required> 
            </input>
            </div>
EOD;
}

function selectField($label, $idname, $valuearray=''){
    $optionlist = '';
    if($valuearray != '') {
        foreach ($valuearray as $entry => $value) {
            $optionlist .= "<option value=$value>$entry</option>";
        }
    }
    return <<<EOD
            <div class="form-group">
            <label for="$idname">$label</label>
            <select class="form-control" id="$idname" name="$idname">
                $optionlist
            </select>
            </div>
EOD;
}

function dateField($label, $idname, $value=''){
    return <<<EOD
            <div class="form-group">
            <label for="$idname">$label</label>
            <input type="date" class="form-control" id="$idname" name="$idname" value="$value" required> 
            </input>
            </div>
EOD;
}

function timeField($label, $idname, $value=''){
    return <<<EOD
            <div class="form-group">
            <label for="$idname">$label</label>
            <input type="time" class="form-control" id="$idname" name="$idname" value="$value" required> 
            </input>
            </div>
EOD;
}