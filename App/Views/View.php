<?php
namespace App\Views;

/**
 * Base View class
 * @author ad
 *
 */
abstract class View
{
	protected $html = '';
	protected $tags = [];

	public abstract function render();

	public function getHtml()
	{
		return $this->html;
	}
	
	public function addTag($tag_name, $value)
	{
		$this->tags['[' . $tag_name . ']'] = $value;
	}
	
	public function setTags(array $tags)
	{
		$this->tags = [];
		foreach ($tags as $tag_key => $tag_value) {
			$this->tags['[' . $tag_key . ']'] = $tag_value;
		}
		
	}

	public function getTemplate($template_name)
	{
		$html = file_get_contents(
			TEMPLATES_PATH . DIRECTORY_SEPARATOR . $template_name . '.html'
		);
		$replaced_html = str_replace(
			array_keys($this->tags),
			array_values($this->tags),
			$html);
		
		// clear unused tags
		$replaced_html = preg_replace('/\[\w+\]/i', '', $replaced_html);
		
		return $replaced_html;
		
	}
}