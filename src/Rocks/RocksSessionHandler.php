<?php
namespace Rocks;

use Scotch\Caching\MemoryCache as MemoryCache;

use Rocks\Repositories\SessionRepository as SessionRepository;

class RocksSessionHandler 
{
	private $sessionRepository;
	private $initSessionData;
	private $savePath;
	private $memCache;
	
	public function __construct()
	{
		register_shutdown_function("session_write_close");
		
		/*
            $this->memcache = new Memcache;
            $this->lifeTime = intval(ini_get("session.gc_maxlifetime"));
            $this->initSessionData = null;
            $this->memcache->connect("127.0.0.1",11211);
		*/
		
		$this->sessionRepository = new SessionRepository();
		
		$this->memCache = new MemoryCache();
	}
	
    public function open($savePath, $sessionName)
    {
		/*
        $sessionID = session_id();
		$hasSession = true;
		if ($sessionID !== "") 
		{
			$this->initSessionData = $this->read($sessionID);
		}
		*/
        return true;
    }

    public function close()
    {
		$this->lifeTime = null;
		$this->memcache = null;
		$this->initSessionData = null;

        return true;
    }

    public function read($id)
    {
		$data = null;
		$rs = $this->sessionRepository->getSession(array("sessionID"=>$id));
		
		if(!$rs->isEmpty())
		{
			$row = $rs->getNextRow();
			$data = $row["sessionData"];
		}
		
		return $data;
    }

    public function write($id, $data)
    {
		$this->sessionRepository->updateSession(array( "sessionID" => $id, "sessionData" => $data));		
		return true;
    }

    public function destroy($id)
    {
		$this->sessionRepository->deleteSession(array( "sessionID" => $id ));

        return true;
    }

    public function gc($maxlifetime)
    {
		$this->sessionRepository->deleteExpiredSessions(array("maxLifetime" => $maxlifetime ));
        return true;
    }
	
}
?>