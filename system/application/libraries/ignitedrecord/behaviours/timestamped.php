<?php
/*
 * Created on 2008 June 30
 * by Jonas Flodén <jonas@koalasoft.se>
 */
/* 
 * Copyright (c) 2008, Jonas Flodén
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * The name of Jonas Flodén may not be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY Jonas Flodén ``AS IS'' AND ANY
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL Jonas Flodén BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */
/**
 * @addtogroup IgnitedRecord
 * @{
 */
/**
 * Timestamp behaviour for IgnitedRecord
 * 
 * Provides created and updated timestamps for record
 * Usage: 
 *  var $__act_as = 'timestamped'; - Use default table columns: created_at and updated_at
 *  var $__act_as = array('timestamped'=>array('created_at'=>'added','updated_at'=>'changed')); - To specify column names
 *
 * The timestamps will be added to IgnitedRecord_record object with the defined column names.
 * So with the default column names:
 * echo object->updated_at;
 * Note: Even though these values can be changed, the changes will not propagate to the database on save().
 * So the following code can be a little confusing:
 * $obj = $this->my_model->new_record();
 * $obj->prop = value;
 * $obj->save();
 * $obj->created_at = '1980-01-01 00:00';
 * $obj->save();
 * echo $obj->created_at; // This will print 1980-01-01 00:00 even though the timestamp in the database is different
 *
 * 
 * @author Jonas Flodén <jonas@koalasoft.se>
 * @par Copyright
 * Copyright (c) 2008, Jonas Flodén <jonas@koalasoft.se>
 * 
 * Eddie: Modification - couldn't unset updated_at before, I added the if() check everywhere
 * 
 */
class IgnitedRecord_timestamped
{
	var $opts;

	function IgnitedRecord_timestamped(&$ORM, $opts)
	{
		// set opts
		$opts['created_at'] = isset($opts['created_at']) ? $opts['created_at'] : 'created_at';
		$opts['updated_at'] = isset($opts['updated_at']) ? $opts['updated_at'] : 'updated_at';

		$this->opts = $opts;

		// hooks
		$ORM->add_hook('save_pre_insert',array(&$this,'_pre_insert'));
		$ORM->add_hook('save_pre_update',array(&$this,'_pre_update'));
		$ORM->add_hook('save_post_insert',array(&$this,'_post_insert'));
		$ORM->add_hook('save_post_update',array(&$this,'_post_update'));
		log_message('debug','IgnitedRecord: Behaviour class IgnitedRecord_timestamped has been initialized');
	}

	function _pre_insert($data)
	{
		$this->time = date('Y-m-d H:i:s');
		if($this->opts['created_at'])
			$data[$this->opts['created_at']] = $this->time;
		
		if($this->opts['updated_at'])
			$data[$this->opts['updated_at']] = $this->time;
	}

	function _pre_update($data)
	{
		$this->time = date('Y-m-d H:i:s');
		if($this->opts['updated_at'])
			$data[$this->opts['updated_at']] = $this->time;
		// Prevent user from updating the 'created_at' column
		unset($data[$this->opts['created_at']]); 
	}

	function _post_insert($object)
	{
		if($this->opts['created_at'])
			$object->{$this->opts['created_at']} = $this->time;
		
		if($this->opts['updated_at'])
			$object->{$this->opts['updated_at']} = $this->time;
	}

	function _post_update($object)
	{
		if($this->opts['updated_at'])
			$object->{$this->opts['updated_at']} = $this->time;
	}
}
/* End of file ignitedrecord_timestamped.php */