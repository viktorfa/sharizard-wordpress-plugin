<?php

namespace SharizardWordpress_Tests_Wpunit\SharizardWordpress\Common\Utilities;

use Codeception\TestCase\WPTestCase;
use SharizardWordpress_Tests_Support\WpunitTester;
use SharizardWordpress\Common\Utilities\Posts;

class PostsTest extends WPTestCase {

	/**
	 * @var WpunitTester
	 */
	protected $tester;

	/**
	 * @inheritDoc
	 */
	public function setUp(): void {
		// Before...
		parent::setUp();
	}

	/**
	 * @inheritDoc
	 */
	public function tearDown(): void {
		// Your tear down methods here.

		// Then...
		parent::tearDown();
	}

	/**
	 * @return Posts
	 */
	private function make_instance() {
		return new Posts();
	}

	/**
	 * @test
	 */
	public function it_should_be_instantiatable() {
		$sut = $this->make_instance();

		$this->assertInstanceOf( Posts::class, $sut );
	}

}