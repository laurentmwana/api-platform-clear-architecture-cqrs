<?php

namespace App\IdentityAndAccess\Domain\ValueObject;

use App\IdentityAndAccess\Domain\Enums\RoleUserEnum;
use App\SharedContext\Domain\Exception\ValueObjectInvalidException;
use Stringable;

final class Roles implements Stringable
{
   /**
    * @var list<string>
    */
   private array $values;

   /**
    * @param list<string> $roles
    */
   private function __construct(array $roles)
   {
      $this->values = $roles;
   }

   public static function default(): self
   {
      return new self([RoleUserEnum::ROLE_USER->value]);
   }

   /**
    * @param array<int, mixed> $roles
    */
   public static function fromArray(array $roles): self
   {
      if (empty($roles)) {
         return self::default();
      }

      $validated = [];

      foreach ($roles as $role) {
         if (!RoleUserEnum::tryFrom($role)) {
            throw new ValueObjectInvalidException('Invalid role: ' . $role);
         }

         $validated[] = $role;
      }

      $validated[] = RoleUserEnum::ROLE_USER->value;

      return new self(array_values(array_unique($validated)));
   }

   public static function fromJson(string $roles): self
   {
      $decoded = json_decode($roles, true);

      if (!is_array($decoded)) {
         throw new ValueObjectInvalidException('Invalid JSON for roles');
      }

      return self::fromArray($decoded);
   }

   public function has(string $role): bool
   {
      return in_array($role, $this->values, true);
   }

   public function add(string $role): self
   {
      if (!RoleUserEnum::tryFrom($role)) {
         throw new ValueObjectInvalidException('Invalid role: ' . $role);
      }

      if ($this->has($role)) {
         return $this;
      }

      return new self([...$this->values, $role]);
   }

   public function remove(string $role): self
   {
      if ($role === RoleUserEnum::ROLE_USER->value) {
         return $this;
      }

      $new = array_filter(
         $this->values,
         fn(string $r): bool => $r !== $role
      );

      return new self(array_values($new));
   }

   /**
    * @return list<string>
    */
   public function toArray(): array
   {
      return $this->values;
   }

   public function toJson(): string
   {
      return json_encode($this->values, JSON_THROW_ON_ERROR);
   }

   public function equals(self $other): bool
   {
      return $this->values === $other->values;
   }

   public function isSimpleUser(): bool
   {
      return $this->values === [RoleUserEnum::ROLE_USER->value];
   }

   public function __toString(): string
   {
      return $this->toJson();
   }
}
