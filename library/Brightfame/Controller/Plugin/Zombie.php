<?php

/**
 * Brightfame
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.
 *
 * @category   Brightfame
 * @package    Controller_Plugin
 * @copyright  Copyright (c) 2009 Rob Morgan. (http://brightfamecms.com)
 * @license    New BSD License
 */

/**
 * When zombie attack conditions are met, issues a head not found response,
 * meaning no body, with a 404 status. Poetry.
 * 
 * Based on: http://coderack.org/users/MetaSkills/entries/15-zombie-shotgun.
 * 
 * @category   Brightfame
 * @package    Controller_Plugin
 * @copyright  Copyright (c) 2009 Rob Morgan. (http://brightfamecms.com)
 * @license    New BSD License
 */
class Brightfame_Controller_Plugin_Zombie extends Zend_Controller_Plugin_Abstract
{
}

/* 
 * @todo - convert ruby code to php.
 * module Rack
  class ZombieShotgun
    
    ZOMBIE_AGENTS = [
/FrontPage/,
/Microsoft Office Protocol Discovery/,
/Microsoft Data Access Internet Publishing Provider/
    ].freeze
 
    ZOMBIE_DIRS = ['_vti_bin','MSOffice','verify-VCNstrict','notified-VCNstrict'].to_set.freeze
    
    attr_reader :options, :request, :agent
    
    def initialize(app, options={})
      @app, @options = app, {
        :agents => true,
        :directories => true
      }.merge(options)
    end
    
    def call(env)
      @agent = env['HTTP_USER_AGENT']
      @request = Rack::Request.new(env)
      zombie_attack? ? head_not_found : @app.call(env)
    end
    
    
    private
    
    def head_not_found
      [404, {"Content-Length" => "0"}, []]
    end
 
    def zombie_attack?
      zombie_dir_attack? || zombie_agent_attack?
    end
 
    def zombie_dir_attack?
      path = request.path_info
      options[:directories] && ZOMBIE_DIRS.any? { |dir| path.include?("/#{dir}/") }
    end
 
    def zombie_agent_attack?
      options[:agents] && agent && ZOMBIE_AGENTS.any? { |za| agent =~ za }
    end
    
  end
end
 
 */