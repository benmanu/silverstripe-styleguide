<?php
interface StyleGuide {

	public function setURL($url);

	public function getNavigation();

	public function getSection($reference);

	public function getSections();

	public function getSectionChildren($reference, $levelsDown = null);

}
