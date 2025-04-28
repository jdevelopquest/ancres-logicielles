<?php

namespace App\Models;

use App\Application\Database;
use Exception;

/**
 *
 */
class GradeModel
{
    /**
     * @param int $idPost
     * @param int $idAccount
     * @return bool
     * @throws Exception
     */
    public function registerGrade(int $idPost, int $idAccount): bool
    {
        $request = "INSERT INTO Grades(idPost,idAccount) VALUES (:idPost,:idAccount)";
        $params = ["idPost" => $idPost, "idAccount" => $idAccount];
        return Database::execute($request, $params);
    }

    /**
     * @param int $idPost
     * @param int $idAccount
     * @return mixed
     * @throws Exception
     */
    public function getGrade(int $idPost, int $idAccount): mixed
    {
        $request = "SELECT * FROM Grades WHERE idPost = :idPost AND idAccount = :idAccount";
        $params = ["idPost" => $idPost, "idAccount" => $idAccount];
        return Database::fetch($request, $params);
    }

    /**
     * @param int $idGrade
     * @return bool
     * @throws Exception
     */
    public function upGrade(int $idGrade): bool
    {
        $request = "UPDATE Grades SET gradeUp = 1, gradeDown = 0, gradeReported = 0 WHERE idGrade = :idGrade";
        $params = ["idGrade" => $idGrade];
        return Database::execute($request, $params);
    }

    /**
     * @param int $idGrade
     * @return bool
     * @throws Exception
     */
    public function downGrade(int $idGrade): bool
    {
        $request = "UPDATE Grades SET gradeUp = 0, gradeDown = 1, gradeReported = 0 WHERE idGrade = :idGrade";
        $params = ["idGrade" => $idGrade];
        return Database::execute($request, $params);
    }

    /**
     * @param int $idGrade
     * @return bool
     * @throws Exception
     */
    public function reportGrade(int $idGrade): bool
    {
        $request = "UPDATE Grades SET gradeUp = 0, gradeDown = 0, gradeReported = 1 WHERE idGrade = :idGrade";
        $params = ["idGrade" => $idGrade];
        return Database::execute($request, $params);
    }
}
