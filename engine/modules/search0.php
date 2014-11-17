<?php
		$sphinx_story=chr(224).chr(241).chr(255);
		$sphinx_story=mb_strtolower($sphinx_story);
		//echo ord($sphinx_story2);
		//die();
                $limit = 10;
                require_once ("sphinx/sphinxapi.php");
                $sphinx = new SphinxClient();
                $sphinx->SetServer('localhost', 3312);
                $sphinx->SetMatchMode(SPH_MATCH_EXTENDED2);
                $sphinx->SetSortMode(SPH_SORT_RELEVANCE);
                $sphinx->SetLimits(0,40,40);

                $sphinx->SetFieldWeights(array('title' => 20, 'title2' => 15, 'short_story' => 10, 'full_story' => 10));
                $result = $sphinx->Query($sphinx_story,'rumedia_post,delta');
//                $result = $sphinx->status();
                $ids=array();
                foreach ($result['matches'] as $id=>$v)$ids[]=$id;
                echo implode(',',$ids)."\n<br>";
                print_r($result);

?>