<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

use Znojil\RevolutBusiness\Enum\TeamMemberState;

/**
 * @phpstan-type TeamMemberResponseData array{id: string, email: string, first_name?: string, last_name?: string, state: string, role_id: string, created_at: string, updated_at: string, department_id?: string, manager_id?: string}
 */
final readonly class TeamMemberDTO{

	/**
	 * @param TeamMemberResponseData $data
	 * @throws \Znojil\RevolutBusiness\Exception\UnexpectedValueException
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			$data['id'],
			$data['email'],
			$data['first_name'] ?? null,
			$data['last_name'] ?? null,
			\Znojil\RevolutBusiness\Internal\EnumMapper::from(TeamMemberState::class, $data['state']),
			$data['role_id'],
			new \DateTimeImmutable($data['created_at']),
			new \DateTimeImmutable($data['updated_at']),
			$data['department_id'] ?? null,
			$data['manager_id'] ?? null
		);
	}

	public function __construct(
		public string $id,
		public string $email,
		public ?string $firstName,
		public ?string $lastName,
		public TeamMemberState $state,
		public string $roleId,
		public \DateTimeImmutable $createdAt,
		public \DateTimeImmutable $updatedAt,
		public ?string $departmentId,
		public ?string $managerId
	){}

}
