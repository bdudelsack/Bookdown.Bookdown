<?php
namespace Bookdown\Bookdown;

use Bookdown\Bookdown\Content\PageFactory;
use Bookdown\Bookdown\Config\IndexConfig;
use Bookdown\Bookdown\Config\RootConfig;

class BookImageFixture
{
    public $rootConfigFile = '/path/to/bookdown.json';
    public $rootConfigData = '{
        "title": "Example Book",
        "content": [
            {"chapter": "chapter/bookdown.json"}
        ],
        "target": "/_site",
        "rootHref": "/",
        "templates": {
            "foo": "/foo.php"
        }
    }';
    public $rootConfig;
    public $rootPage;

    public $indexConfigFile = '/path/to/chapter/bookdown.json';
    public $indexConfigData = '{
        "title": "Chapter",
        "content": [
            {"section": "section.md"}
        ]
    }';
    public $indexConfig;
    public $indexPage;

    public $pageFile = '/path/to/chapter/section.md';
    public $pageData = '# Title

Text under title.

## Subtitle `code` A

Text under subtitle A.

### Sub-subtitle

Text under sub-subtitle.

![Build Status](http://test.dev/img/test1.jpg)
![Build Status](https://test.dev/test2.jpg)
![Build Status](//test.dev/img/test3.jpg)
![Build Status](/img/test4.jpg)
![Build Status](../img/test5.jpg)

## Subtitle B

Text under subtitle B.
';
    public $page;

    public function __construct(FakeFsio $fsio)
    {
        $pageFactory = new PageFactory();

        $fsio->put($this->rootConfigFile, $this->rootConfigData);
        $this->rootConfig = new RootConfig($this->rootConfigFile, $this->rootConfigData);
        $this->rootPage = $pageFactory->newRootPage($this->rootConfig);

        $fsio->put($this->indexConfigFile, $this->indexConfigData);
        $this->indexConfig = new IndexConfig($this->indexConfigFile, $this->indexConfigData);
        $this->indexPage = $pageFactory->newIndexPage(
            $this->indexConfig,
            'chapter',
            $this->rootPage,
            1
        );
        $this->rootPage->setNext($this->indexPage);
        $this->rootPage->addChild($this->indexPage);
        $this->indexPage->setPrev($this->rootPage);

        $fsio->put($this->pageFile, $this->pageData);
        $this->page = $pageFactory->newPage(
            $this->pageFile,
            'section',
            $this->indexPage,
            1
        );

        $this->indexPage->addChild($this->page);
        $this->indexPage->setNext($this->page);
        $this->page->setPrev($this->indexPage);
    }
}
