<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness;

interface TokenStorage{

	public function load(): ?TokenPair;

	public function save(TokenPair $tokenPair): void;

}
