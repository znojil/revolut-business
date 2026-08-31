<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

/**
 * @phpstan-type TeamMemberInvitationResponseData array{id: string, email: string, role_id: string, created_at: string, updated_at: string}
 */
final readonly class TeamMemberInvitationDTO{

	/**
	 * @param TeamMemberInvitationResponseData $data
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			$data['id'],
			$data['email'],
			$data['role_id'],
			new \DateTimeImmutable($data['created_at']),
			new \DateTimeImmutable($data['updated_at'])
		);
	}

	public function __construct(
		public string $id,
		public string $email,
		public string $roleId,
		public \DateTimeImmutable $createdAt,
		public \DateTimeImmutable $updatedAt
	){}

}
