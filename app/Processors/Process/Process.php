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
		// edit the links in the head
		$this->manageHead();
		// edit the srcs on the body
		$this->manageBodySrc();
		// edit the hrefs on the body
		$this->manageBodyHref();

		// return the finalized content
		return $this->content;
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
			$result = file_get_contents($this->link);
			$this->content = $result;
		}
		catch (\Exception $e)
		{
			$this->content = $e;
		}
	}

	/**
	 * [manageHead description]
	 * @return [type] [description]
	 */
	protected function manageHead()
	{
		$start_head = strpos($this->content, "<head");
		$end_head = stripos($this->content, "</head");

		$head = substr($this->content, $start_head, $end_head);
		$needles = [ 'href="', "href='", 'src="', "src='" ];
		
		$head = $this->addHost($head, $needles, $this->host, 0);

		$this->content = substr_replace($this->content, $head, $start_head, $end_head);
	}

	protected function manageBodySrc()
	{
		$start_body = strpos($this->content, "<body");
		$end_body = stripos($this->content, "</body");

		$body = substr($this->content, $start_body, $end_body);
		$needles = [ 'src="', "src='" ];

		$body = $this->addHost($body, $needles, $this->host, 0);
		$this->content = substr_replace($this->content, $body, $start_body, $end_body);
	}

	protected function manageBodyHref()
	{
		$start_body = strpos($this->content, "<body");
		$end_body = stripos($this->content, "</body");

		$body = substr($this->content, $start_body, $end_body);
		$needles = [ 'href="', "href='" ];

		$body = $this->addLink($body, $needles, $this->host, 0);
		$this->content = substr_replace($this->content, $body, $start_body, $end_body);
	}

	/**
	 * [repHead description]
	 * @param  [type]  $head    [description]
	 * @param  [type]  $needles [description]
	 * @param  [type]  $host    [description]
	 * @param  integer $lp      [description]
	 * @return [type]           [description]
	 */
	public static function addHost($head, $needles, $host, $lp=0)
	{
		foreach ($needles as $needle) {
			while (($lp = strpos($head, $needle, $lp))!== false) {
				if (substr($head, ($lp + strlen($needle)), 4) != 'http')
					$head = substr_replace($head, (substr($head, ($lp + strlen($needle)), 1) == '/') ? "//$host" : "//$host/", ($lp + strlen($needle)), 0);
				$lp = $lp + strlen($needle);
			}
		}
		return $head;
	}
	public static function addLink($body, $needles, $host, $lp=0)
	{
		foreach ($needles as $needle)
		{
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