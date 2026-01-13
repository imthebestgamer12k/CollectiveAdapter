<?php

namespace Niel\CollectiveAdapter\Services;

class FormService
{
    protected $model;

    public function model($model, array $attributes = [])
    {
        $this->model = $model;
        return $this->open($attributes);
    }

    // Generate the opening form tag, without overriding action and method
    public function open(array $attributes = [])
    {
        // Handle 'route' as an array (if passed as an array)
        $action = '';
        if (isset($attributes['route'])) {
            if (is_array($attributes['route'])) {
                $route = $attributes['route'][0];
                $params = array_slice($attributes['route'], 1);
                $action = 'action="' . route($route, $params) . '"';
            } else {
                $action = 'action="' . route($attributes['route']) . '"';
            }
        } elseif (isset($attributes['url'])) {
            if (is_array($attributes['url'])) {
                $url = $attributes['url'][0];
                $params = array_slice($attributes['url'], 1);
                $action = 'action="' . url($url, $params) . '"';
            } else {
                $action = 'action="' . url($attributes['url']) . '"';
            }
        }

        // Handle 'method' (POST is default)
        $method = 'POST';
        $append = '';
        if (isset($attributes['method'])) {
            $methodVal = strtoupper($attributes['method']);
            if ($methodVal === 'GET') {
                $method = 'GET';
            } elseif ($methodVal !== 'POST') {
                $append = '<input type="hidden" name="_method" value="' . $methodVal . '" />';
            }
        }
        $methodAttr = 'method="' . $method . '"';

        // Handle 'files' => true for multipart/form-data
        if (isset($attributes['files']) && $attributes['files']) {
            $attributes['enctype'] = 'multipart/form-data';
            unset($attributes['files']);
        }

        // Remove 'route' and 'method' from attributes since we've already handled them
        unset($attributes['route'], $attributes['method'], $attributes['url']);

        // Parse the remaining attributes (class, id, etc.)
        $attributesString = $this->parseAttributes($attributes);

        return "<form $action $methodAttr $attributesString>$append";
    }

    // Generate the closing form tag
    public function close()
    {
        return '</form>';
    }

    // Generate an input field
    public function input($type, $name, $value = null, array $attributes = [])
    {
        $value = $this->getValue($name, $value);
        $attributesString = $this->parseAttributes($attributes);
        return "<input type=\"$type\" name=\"$name\" value=\"" . e($value) . "\" $attributesString />";
    }

    public function text($name, $value = null, array $attributes = [])
    {
        $value = $this->getValue($name, $value);
        $attributesString = $this->parseAttributes($attributes);
        return "<input type=\"text\" name=\"$name\" value=\"" . e($value) . "\" $attributesString />";
    }

    public function email($name, $value = null, array $attributes = [])
    {
        $value = $this->getValue($name, $value);
        $attributesString = $this->parseAttributes($attributes);
        return "<input type=\"email\" name=\"$name\" value=\"" . e($value) . "\" $attributesString />";
    }

    public function number($name, $value = null, array $attributes = [])
    {
        $value = $this->getValue($name, $value);
        $attributesString = $this->parseAttributes($attributes);
        return "<input type=\"number\" name=\"$name\" value=\"" . e($value) . "\" $attributesString />";
    }

    public function date($name, $value = null, array $attributes = [])
    {
        $value = $this->getValue($name, $value);
        $attributesString = $this->parseAttributes($attributes);
        return "<input type=\"date\" name=\"$name\" value=\"" . e($value) . "\" $attributesString />";
    }

    public function password($name, $value = null, array $attributes = [])
    {
        $attributesString = $this->parseAttributes($attributes);
        return "<input type=\"password\" name=\"$name\" value=\"" . e($value) . "\" $attributesString />";
    }

    public function hidden($name, $value = null, array $attributes = [])
    {
        $value = $this->getValue($name, $value);
        $attributesString = $this->parseAttributes($attributes);
        return "<input type=\"hidden\" name=\"$name\" value=\"" . e($value) . "\" $attributesString />";
    }

    public function checkbox($name, $value = 1, $checked = false, array $attributes = [])
    {
        $attributesString = $this->parseAttributes($attributes);
        $checkedAttr = $checked ? ' checked' : '';
        return "<input type=\"checkbox\" name=\"$name\" value=\"$value\"$checkedAttr $attributesString />";
    }

    public function radio($name, $value, $checked = false, array $attributes = [])
    {
        $attributesString = $this->parseAttributes($attributes);
        $checkedAttr = $checked ? ' checked' : '';
        return "<input type=\"radio\" name=\"$name\" value=\"$value\"$checkedAttr $attributesString />";
    }

    public function file($name, array $attributes = [])
    {
        $attributesString = $this->parseAttributes($attributes);
        return "<input type=\"file\" name=\"$name\" $attributesString />";
    }

    public function picture($name, array $attributes = [])
    {
        return $this->file($name, $attributes);
    }

    public function submit($value = null, array $attributes = [])
    {
        $attributesString = $this->parseAttributes($attributes);
        return "<input type=\"submit\" value=\"" . e($value) . "\" $attributesString />";
    }

    public function reset($value = null, array $attributes = [])
    {
        $attributesString = $this->parseAttributes($attributes);
        return "<input type=\"reset\" value=\"" . e($value) . "\" $attributesString />";
    }

    public function button($value = null, array $attributes = [])
    {
        $attributesString = $this->parseAttributes($attributes);
        return "<button type=\"button\" $attributesString>" . e($value) . "</button>";
    }

    public function tel($name, $value = null, array $attributes = [])
    {
        return $this->input('tel', $name, $value, $attributes);
    }

    public function url($name, $value = null, array $attributes = [])
    {
        return $this->input('url', $name, $value, $attributes);
    }

    public function search($name, $value = null, array $attributes = [])
    {
        return $this->input('search', $name, $value, $attributes);
    }

    public function color($name, $value = null, array $attributes = [])
    {
        return $this->input('color', $name, $value, $attributes);
    }

    public function time($name, $value = null, array $attributes = [])
    {
        return $this->input('time', $name, $value, $attributes);
    }

    // Generate a label tag
    public function label($for, $text, array $attributes = [])
    {
        $attributesString = $this->parseAttributes($attributes);
        return "<label for=\"$for\" $attributesString>$text</label>";
    }

    // Generate a textarea
    public function textarea($name, $value = null, array $attributes = [])
    {
        $value = $this->getValue($name, $value);
        $attributesString = $this->parseAttributes($attributes);
        return "<textarea name=\"$name\" $attributesString>" . e($value) . "</textarea>";
    }

    // Generate a select dropdown
    public function select($name, $options = [], $selected = null, array $attributes = [])
    {
        $selected = $this->getValue($name, $selected);
        $attributesString = $this->parseAttributes($attributes);
        $html = "<select name=\"$name\" $attributesString>";

        foreach ($options as $key => $value) {
            $selectedAttr = $key == $selected ? ' selected' : '';
            $html .= "<option value=\"$key\"$selectedAttr>$value</option>";
        }

        $html .= '</select>';
        return $html;
    }

    // Parse attributes into an HTML string
    private function parseAttributes(array $attributes)
    {
        $attributesString = '';

        foreach ($attributes as $key => $value) {
            if (is_array($value)) {
                $value = implode(' ', $value);
            }
            $attributesString .= "$key=\"$value\" ";
        }

        return rtrim($attributesString);
    }

    private function getValue($name, $value)
    {
        if ($value !== null) {
            return $value;
        }

        if (is_array($this->model)) {
            return $this->model[$name] ?? '';
        }

        if (is_object($this->model)) {
            return $this->model->{$name} ?? '';
        }

        return '';
    }
}