<?php

namespace App\Processors;

use App\Page;
use App\Content;
use App\Processors\Process\Process;

class Processor
{

	/**
	 * The time interval for the content to 
	 * be updated
	 * 
	 * @var integer
	 */
	protected $time = 15;

	/**
	 * the page instance
	 * 
	 * @var [class]
	 */
	protected $page;

	/**
	 * The content for the specified 
	 * page instacnce
	 * 
	 * @var [class]
	 */
	protected $content;

	/**
	 * Initialize the processor with an instance of
	 * the page class
	 * 
	 * @param Page $page [description]
	 */
	public function __construct(Page $page)
	{
		$this->page = $page;
		$this->content = $page->content;
	}

	/**
	 * This is what determines what content gets
	 * delivered.
	 * 
	 * @return [type] [description]
	 */
	public function process()
	{
		// if there is no content (new link)
		if (is_null($this->content))
			return $this->generateContent();

		// get the last updated date
		$last_update = date('c', strtotime($this->content->updated_at));
		// get the date time days ago
		$now = date('c', strtotime("-{$this->time} days"));

		// if the content hasn't been updated in $time days
		if ($now >= $last_update)
			return $this->updateContent();
		
		// this content is up to date so return it
		return $this->content;
	}

	/**
	 * This would generate content for a new
	 * url 
	 * 
	 * @return [string] [description]
	 */
	protected function generateContent()
	{
		// process the link and get content
		$process = new Process($this->page->link);
		$result = $process->dispatch();

		return $result;

		// store the content in the database
		$content = new Content;
		$content->content = $result;

		try
		{
			$this->page->content()->save($content);
		}
		catch (\Exception $e)
		{
			return $e;
		}

		// return the gotten content
		return $result;
	}

	/**
	 * This generates content for a page that hasn't 
	 * been updated in a long time.
	 * 
	 * @return [type] [description]
	 */
	protected function updateContent()
	{
		// process the link and get content
		$process = new Process($this->page->link);
		$result = $process->dispatch();

		// store the content in the database
		$this->content->content = $result;
		$this->content->save();

		// return the gotten content
		return $result;
	}

}