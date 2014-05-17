<?php namespace Widget;

use Symfony\Component\DomCrawler\Crawler;
use Carbon\Carbon;

class CurseCrawler extends Crawler {

	/**
	 * Strip out everything but numbers from the text of the node
	 *
	 * @return int
	 */
	public function number()
	{
		return (int) preg_replace('/\D/', '', $this->getNode(0)->nodeValue);
	}

	/**
	 * Strip the key from the text of the node
	 * eg: "License: MIT" becomes "MIT"
	 *
	 * @return string
	 */
	public function value()
	{
		return trim(explode(':', $this->getNode(0)->nodeValue)[1]);
	}

	/**
	 * Format an attribute as an ISO8601 time
	 *
	 * @param string $key
	 * @return string
	 */
	public function attrAsTime($key)
	{
		$timestamp = $this->attr($key);
		return $this->timestampToISO8601($timestamp);
	}

	/**
	 * Check if the node exists
	 *
	 * @return bool
	 */
	public function exists()
	{
		return (count($this)) ? true : false;
	}

	/**
	 * Convert unix timestamp to ISO8601
	 *
	 * @param $timestamp
	 * @return string
	 */
	private function timestampToISO8601($timestamp)
	{
		return Carbon::createFromTimestamp($timestamp)->toISO8601String();
	}

	/**
	 * Extend each to remove values that are null
	 *
	 * @param callable $closure
	 * @return array
	 */
	public function eachWithoutNull(\Closure $closure)
	{
		$data = $this->each($closure);
		return array_filter($data, 'is_array');
	}

	public function finalUrlSegment($key)
	{
		$segments = explode('/', $this->attr($key));
		return end($segments);
	}

}