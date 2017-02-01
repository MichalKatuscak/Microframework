<?php

namespace Katuscak\Component;

use Katuscak\Bridge\AuthorizationConfiguration;
use Katuscak\Component\Routing\Router;
use Katuscak\Kernel\Factory;

final class Authorization
{
    /** @var array $configuration */
    private $configuration;

    /** @var array $roles */
    private $roles;

    /** @var Router $router */
    private $router;

    /** @var Session $session */
    private $session;

    /** @var array $user */
    private $user;

    /** @var Database $database */
    private $database;

    public function __construct(Database $database, Session $session, AuthorizationConfiguration $configuration)
    {
        $this->user = null;
        $this->configuration = $configuration;
        $this->session = $session;
        $this->database = $database;

        $this->setUser();
        $this->parseRoles();
    }

    public function isGranted(string $role, bool $automatic_redirect = true): bool
    {
        $this->router = Factory::load(Router::class);

        if (!$this->user || !in_array($role, $this->roles[$this->user["role"]])) {
            if ($automatic_redirect) {
                $this->router->redirectToRoute($this->configuration->get("login_route"));
            }
            return false;
        }

        return true;
    }

    private function parseRoles()
    {
        $roles = [];

        foreach ($this->configuration->get("roles") as $role => $containing_roles) {
            $roles[$role] = [$role];

            foreach ($containing_roles as $containing_role) {
                foreach ($roles as $parent_role => $parents_roles) {
                    if (in_array($role, $parents_roles) && $parent_role != $role) {
                        $roles[$parent_role][] = $containing_role;
                    }
                }

                $roles[$role][] = $containing_role;
            }
        }

        $this->roles = $roles;
    }

    private function setUser()
    {
        if ($user = $this->session->get("user")) {
            $this->user = $user;
        }
    }

    public function login($username, $password): bool
    {
        $db = $this->database;

        $table = $this->configuration->get("database")["table"];
        $column_username = $this->configuration->get("database")["column_username"];
        $column_password = $this->configuration->get("database")["column_password"];

        $result = $db->query("SELECT * FROM `$table` WHERE `$column_username` = %0", $username);

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            $password_from_database = $user[$column_password];
            $status = password_verify($password, $password_from_database);
            $this->session->set("user", $user);

            return $status;
        }
        return false;
    }
}