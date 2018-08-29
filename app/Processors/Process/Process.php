<?php

namespace App\Processors\Process;

class Process
{
	
	/**
	 * the link to the site that needs to be cloned
	 * 
	 * @var [type]
	 */
	protected $link;

	/**
	 * the title property would hold the title of the page
	 * 
	 * @var string
	 */
	protected $title = 'default title';

	/**
	 * the domain (host)
	 * 
	 * @var [type]
	 */
	protected $host;

	/**
	 * the content generated from the cloning
	 * 
	 * @var [type]
	 */
	protected $content;

	/**
	 * the construct of the class
	 * 
	 * @param [type] $link [description]
	 */
	public function __construct($link)
	{
		$this->link = $link;
		$this->host = parse_url($link)["host"];
	}

	/**
	 * dispatch the methods in teh proper 
	 * order.
	 * 
	 * @return [type] [description]
	 */
	public function dispatch()
	{
		// get the content and store it in the content property
		$this->getContent();
		// get the title of the content
		$this->getTitle();
		// edit the links in the head
		$this->manageHead();
		// edit the srcs on the body
		$this->manageBodySrc();
		// edit the hrefs on the body
		$this->manageBodyHref();

		// return the finalized content
		return [ "title" => $this->title, "content" => $this->content ];
	}

	/**
	 * get the content of the link
	 * 
	 * @return [type] [description]
	 */
	protected function getContent()
	{
		try
		{
			// get the html contents
			$result = file_get_contents($this->link);
			// store it in the ocntent property
			$this->content = $result;
		}
		catch (\Exception $e)
		{
			$this->content = $e;
		}
	}

	/**
	 * Get the title of the page from the 
	 * content
	 * 
	 * @return [type] [description]
	 */
	protected function getTitle()
	{
		// store the index of the begingninig head tags
		$start_title = strpos($this->content, "<title>");
		// store the index of the ending title tag
		$end_title = strpos($this->content, "</title>");

		// extract the title
		$title = substr($this->content, ($start_title + 7) , ($end_title - ($start_title + 7)));

		// store the title in the title property
		$this->title = $title;
	}

	/**
	 * Manage the links in the head tags
	 * 
	 * @return [type] [description]
	 */
	protected function manageHead()
	{
		// store the index of the begingninig head tags
		$start_head = strpos($this->content, "<head");
		// store the index of the ending head tag
		$end_head = stripos($this->content, "</head");

		// extract the head tag (haystack)
		$head = substr($this->content, $start_head, $end_head);
		// the substrings we would be looking for (needle)
		$needles = [ 'href="', "href='", 'src="', "src='" ];
		
		// complete the adding of teh domain name to the needles
		$head = $this->addHost($head, $needles, $this->host, 0);

		// update the content to the accurate one.
		$this->content = substr_replace($this->content, $head, $start_head, $end_head);
	}

	/**
	 * Manage the links with the src in teh
	 * body tag
	 * 
	 * @return [type] [description]
	 */
	protected function manageBodySrc()
	{
		// store the index of the begining body tags
		$start_body = strpos($this->content, "<body");
		// store the index of the ending body tag
		$end_body = stripos($this->content, "</body");

		// extract the body tag (haystack)
		$body = substr($this->content, $start_body, $end_body);
		// the substrings we would be looking for (needles)
		$needles = [ 'src="', "src='" ];

		// complete the adding of teh domain name to the needles
		$body = $this->addHost($body, $needles, $this->host, 0);

		// update the content to the accurate one.
		$this->content = substr_replace($this->content, $body, $start_body, $end_body);
	}

	/**
	 * Manage the links with href in the
	 * body tag
	 * 
	 * @return [type] [description]
	 */
	protected function manageBodyHref()
	{
		// store the index of the begining body tags
		$start_body = strpos($this->content, "<body");
		// store the index of the ending body tag
		$end_body = stripos($this->content, "</body");

		// extract the body tag (haystack)
		$body = substr($this->content, $start_body, $end_body);
		// the substrings we would be looking for (needles)
		$needles = [ 'href="', "href='" ];

		// complete the adding of teh /links?url name to the needles
		$body = $this->addLink($body, $needles, $this->host, 0);

		// update the content to the accurate one.
		$this->content = substr_replace($this->content, $body, $start_body, $end_body);
	}

	/**
	 * 
	 * Prepend the host name to the links of the of
	 * of the host.
	 * 
	 * @param  [string]  $head    [description]
	 * @param  [array]  $needles [description]
	 * @param  [string]  $host    [description]
	 * @param  integer $lp      [description]
	 * @return [type]           [description]
	 */
	public static function addHost($head, $needles, $host, $lp=0)
	{
		// loop through the needles
		foreach ($needles as $needle) {
			// foreach of teh needles arrange the things
			while (($lp = strpos($head, $needle, $lp))!== false) {
				if (substr($head, ($lp + strlen($needle)), 4) != 'http')
					$head = substr_replace($head, (substr($head, ($lp + strlen($needle)), 1) == '/') ? "//$host" : "//$host/", ($lp + strlen($needle)), 0);
				$lp = $lp + strlen($needle);
			}
		}
		return $head;
	}

	/**
	 * Prepend the link-url to the host of another
	 * thing
	 * 
	 * @param [string]  $body    [description]
	 * @param [array]  $needles [description]
	 * @param [type]  $host    [description]
	 * @param integer $lp      [description]
	 */
	public static function addLink($body, $needles, $host, $lp=0)
	{
		// loop through the needles
		foreach ($needles as $needle)
		{
			// foreach of teh needles arrange the things
			while (($lp = strpos($body, $needle, $lp))!== false)
			{
				if (substr($body, ($lp + strlen($needle)), 4) == 'http')
					$body = substr_replace($body, "/link?url=", ($lp + strlen($needle)), 0);
				else
					$body = substr_replace($body, (substr($body, ($lp + strlen($needle)), 1) == '/') ? "/link?url=http://$host" : "/link?url=http://$host/", ($lp + strlen($needle)), 0);

				$lp = $lp + strlen($needle);
			}
		}
		return $body;
	}

}