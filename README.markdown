[![Build Status](https://travis-ci.org/josegonzalez/cakephp-ajax-controller.png?branch=master)](https://travis-ci.org/josegonzalez/cakephp-ajax-controller) [![Coverage Status](https://coveralls.io/repos/josegonzalez/cakephp-ajax-controller/badge.png?branch=master)](https://coveralls.io/r/josegonzalez/cakephp-ajax-controller?branch=master) [![Total Downloads](https://poser.pugx.org/josegonzalez/cakephp-ajax-controller/d/total.png)](https://packagist.org/packages/josegonzalez/cakephp-ajax-controller) [![Latest Stable Version](https://poser.pugx.org/josegonzalez/cakephp-ajax-controller/v/stable.png)](https://packagist.org/packages/josegonzalez/cakephp-ajax-controller)

# AjaxController

Automatically support ajax requests from popular Javascript libraries without any more thought.

## Background

In the process of writing a new tutorial for CakePHP 2.0, I realized some of the stuff I was writing could be abstracted into it's own self-contained class. I did so, and moved the logic into the AppController.

Recently I was working on a side-project that would need ajax-enabled JSON responses in CakePHP 1.3. With my previous experience, I knew doing this was possible, but rather than copy-pasting the same code over and over, I rewrote it into a plugin for future reuse and abuse.

## Requirements

* CakePHP 2.x
* PHP 5.3

## Installation

_[Using [Composer](http://getcomposer.org/)]_

Add the plugin to your project's `composer.json` - something like this:

  {
    "require": {
      "josegonzalez/cakephp-ajax-controller": "dev-master"
    }
  }

Because this plugin has the type `cakephp-plugin` set in it's own `composer.json`, composer knows to install it inside your `/Plugins` directory, rather than in the usual vendors file. It is recommended that you add `/Plugins/AjaxController` to your .gitignore file. (Why? [read this](http://getcomposer.org/doc/faqs/should-i-commit-the-dependencies-in-my-vendor-directory.md).)

_[Manual]_

* Download this: [http://github.com/josegonzalez/cakephp-ajax-controller/zipball/master](http://github.com/josegonzalez/cakephp-ajax-controller/zipball/master)
* Unzip that download.
* Copy the resulting folder to `app/Plugin`
* Rename the folder you just copied to `AjaxController`

_[GIT Submodule]_

In your app directory type:

  git submodule add -b master git://github.com/josegonzalez/cakephp-ajax-controller.git Plugin/AjaxController
  git submodule init
  git submodule update

_[GIT Clone]_

In your `Plugin` directory type:

  git clone -b master git://github.com/josegonzalez/cakephp-ajax-controller.git AjaxController

### Enable plugin

In 2.0 you need to enable the plugin your `app/Config/bootstrap.php` file:

  CakePlugin::load('AjaxController');

If you are already using `CakePlugin::loadAll();`, then this is not necessary.

## Usage

Place the following code in your `app/app_controller.php`:

```
App::import('Lib', 'AjaxController.AjaxController');
class AppController extends AjaxController {
}
```

When making Ajax Requests that prefer JSON, the `AjaxController` will automatically convert the entire response into json. Please note that this also includes the view variables that would normally be set. If you'd like to hide any data, do not set it for the view.

This also handles all redirects properly, so your ajax request will be able to perform as a regular request might.

In order to disable JSON responses for Ajax requests, you can either set `$this->_disableAjax = true;` or call `$this->_disableAjax()` with an array of actions to disable JSON responses for.


```
App::import('Lib', 'AjaxController.AjaxController');
class AppController extends AjaxController {
}
class PostsController extends AppController {
    function beforeFilter() {
        $this->_disableAjax('index', 'view');
    }

    function edit() {
        $this->_disableAjax = true;
    }
}
```

## TODO:

* Unit Tests
* Provide XML responses when desired

## License

The MIT License (MIT)

Copyright (c) 2011 Jose Diaz-Gonzalez

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
