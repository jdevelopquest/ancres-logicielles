<?php

namespace App\Models;

use App\Application\Config\AppSettings;
use App\Application\Database;
use Exception;

/**
 *
 */
class PostModel
{
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /// status
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @param $idPost
     * @return bool
     * @throws Exception
     */
    public function banPost($idPost): bool
    {
        $request =
            "UPDATE Posts SET postIsBanned = 1, postIsPublished = 0 WHERE idPost = :idPost";
        $params = [":idPost" => $idPost];
        return Database::execute($request, $params);
    }

    /**
     * @param $idPost
     * @return bool
     * @throws Exception
     */
    public function unbanPost($idPost): bool
    {
        $request =
            "UPDATE Posts SET postIsBanned = 0, postIsPublished = 0 WHERE idPost = :idPost";
        $params = [":idPost" => $idPost];
        return Database::execute($request, $params);
    }

    /**
     * @param $idPost
     * @return bool
     * @throws Exception
     */
    public function publishPost($idPost): bool
    {
        $request =
            "UPDATE Posts SET postIsPublished = 1, postIsBanned = 0 WHERE idPost = :idPost";
        $params = [":idPost" => $idPost];
        return Database::execute($request, $params);
    }

    /**
     * @param $idPost
     * @return bool
     * @throws Exception
     */
    public function unpublishPost($idPost): bool
    {
        $request =
            "UPDATE Posts SET postIsPublished = 0, postIsBanned = 0 WHERE idPost = :idPost";
        $params = [":idPost" => $idPost];
        return Database::execute($request, $params);
    }

    /**
     * @param mixed $idPost
     * @return mixed
     * @throws Exception
     */
    public function getPostStatus(mixed $idPost): mixed
    {
        $request =
            "SELECT
                Posts.idPost,
                postIsPublished,
                postIsBanned
            FROM Posts
            WHERE Posts.idPost = :idPost";
        $params = [":idPost" => $idPost];
        return Database::fetch($request, $params);
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /// softwares
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @param $idPost
     * @return mixed
     * @throws Exception
     */
    public function getSoftwareByIdPost($idPost): mixed
    {
        $request =
            "SELECT
                Posts.idPost,
                idSoftware,
                softwareName,
                softwareSummary,
                postIsPublished,
                postIsBanned
            FROM Posts
            JOIN Softwares on Posts.idPost = Softwares.idPost
            WHERE Posts.idPost = :idPost";
        $params = [":idPost" => $idPost];
        return Database::fetch($request, $params);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getSoftwares(): array
    {
        $request =
            "SELECT
            Posts.idPost,
            idSoftware,
            softwareName,
            softwareSummary,
            postIsPublished,
            postIsBanned
        FROM Posts
        JOIN Softwares on Posts.idPost = Softwares.idPost";

        return Database::fetchAll($request);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getSoftwaresPublished(): array
    {
        $request =
            "SELECT
            Posts.idPost,
            idSoftware,
            softwareName,
            softwareSummary,
            postIsPublished,
            postIsBanned
        FROM Posts
        JOIN Softwares on Posts.idPost = Softwares.idPost
        WHERE postIsPublished = 1";

        return Database::fetchAll($request);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getSoftwaresPublishedAndPending(): array
    {
        $request =
            "SELECT
            Posts.idPost,
            idSoftware,
            softwareName,
            softwareSummary,
            postIsPublished,
            postIsBanned
        FROM Posts
        JOIN Softwares on Posts.idPost = Softwares.idPost
        WHERE postIsBanned = 0";

        return Database::fetchAll($request);
    }

    /**
     * @param int $idAccount
     * @param string $softwareName
     * @param string $softwareSummary
     * @return bool
     * @throws Exception
     */
    public function registerSoftware(int $idAccount, string $softwareName, string $softwareSummary): bool
    {
        if (Database::beginTransaction()) {
            $request =
                "INSERT INTO Posts(
                  postIsBanned, postIsPublished, idAccount) 
                  VALUES (0, 0, :idAccount)";
            $params = [":idAccount" => $idAccount];

            if (Database::execute($request, $params)) {
                $request =
                    "INSERT INTO Softwares(
                        idPost,
                        softwareName,
                        softwareSummary
                    )
                    VALUES(
                        :idPost,
                        :softwareName,
                        :softwareSummary)";

                $params = [
                    ":idPost" => Database::getLastInsertId(),
                    ":softwareName" => $softwareName,
                    ":softwareSummary" => $softwareSummary
                ];

                if (Database::execute($request, $params)) {
                    Database::commit();
                    return true;
                } else {
                    Database::rollback();
                    return false;
                }
            } else {
                Database::rollback();
                return false;
            }
        }

        return false;
    }

    /**
     * @param string $softwareName
     * @return bool
     */
    public function isValidSoftwareName(string $softwareName): bool
    {
        return preg_match(AppSettings::SOFTWARE_NAME_PATTERN, $softwareName) === 1;
    }

    /**
     * @param string $softwareDescription
     * @return bool
     */
    public function isValidSoftwareSummary(string $softwareDescription): bool
    {
        return preg_match(AppSettings::SOFTWARE_SUMMARY_PATTERN, $softwareDescription) === 1;
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /// anchors
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @param int $idPostSoftware
     * @return array
     * @throws Exception
     */
    public function getAnchorsByIdPostSoftware(int $idPostSoftware): array
    {
        $request =
            "SELECT
                Posts.idPost,
                idAnchor,
                anchorUrl,
                anchorContent,
                postIsPublished,
                postIsBanned
            FROM Posts
            JOIN Anchors on Posts.idPost = Anchors.idPost
            WHERE Anchors.idPostSoftware = :idPostSoftware";
        $params = [":idPostSoftware" => $idPostSoftware];
        return Database::fetchAll($request, $params);
    }

    /**
     * @param int $idPostSoftware
     * @return array
     * @throws Exception
     */
    public function getPublishedAnchorsByIdPostSoftware(int $idPostSoftware): array
    {
        $request =
            "SELECT
                Posts.idPost,
                idAnchor,
                anchorUrl,
                anchorContent,
                postIsPublished,
                postIsBanned
            FROM Posts
            JOIN Anchors on Posts.idPost = Anchors.idPost
            WHERE Anchors.idPostSoftware = :idPostSoftware AND postIsPublished = 1 AND postIsBanned = 0";
        $params = [":idPostSoftware" => $idPostSoftware];
        return Database::fetchAll($request, $params);
    }

    /**
     * @param int $idPostSoftware
     * @return array
     * @throws Exception
     */
    public function getPublishedAndPendingAnchorsByIdPostSoftware(int $idPostSoftware): array
    {
        $request =
            "SELECT
                Posts.idPost,
                idAnchor,
                anchorUrl,
                anchorContent,
                postIsPublished,
                postIsBanned
            FROM Posts
            JOIN Anchors on Posts.idPost = Anchors.idPost
            WHERE Anchors.idPostSoftware = :idPostSoftware AND postIsBanned = 0";
        $params = [":idPostSoftware" => $idPostSoftware];
        return Database::fetchAll($request, $params);
    }

    /**
     * @param int $idAccount
     * @param int $idPostSoftware
     * @param string $anchorUrl
     * @param string $anchorContent
     * @return bool
     * @throws Exception
     */
    public function registerAnchor(int $idAccount, int $idPostSoftware, string $anchorUrl, string $anchorContent): bool
    {
        if (Database::beginTransaction()) {
            $request =
                "INSERT INTO Posts(
                  postIsBanned, postIsPublished, idAccount) 
                  VALUES (0, 0, :idAccount)";
            $params = [":idAccount" => $idAccount];

            if (Database::execute($request, $params)) {
                $request =
                    "INSERT INTO Anchors(
                        idPost,
                        idPostSoftware,
                        anchorUrl,
                        anchorContent
                    )
                    VALUES(
                        :idPost,
                           :idPostSoftware,
                           :anchorUrl,
                           :anchorContent)";

                $params = [
                    ":idPost" => Database::getLastInsertId(),
                    ":idPostSoftware" => $idPostSoftware,
                    ":anchorUrl" => $anchorUrl,
                    ":anchorContent" => $anchorContent
                ];

                if (Database::execute($request, $params)) {
                    Database::commit();
                    return true;
                } else {
                    Database::rollback();
                    return false;
                }
            } else {
                Database::rollback();
                return false;
            }
        }

        return false;
    }

    /**
     * @param string $softwareName
     * @return bool
     */
    public function isValidAnchorUrl(string $softwareName): bool
    {
        return preg_match(AppSettings::ANCHOR_URL_PATTERN, $softwareName) === 1;
    }

    /**
     * @param string $softwareDescription
     * @return bool
     */
    public function isValidAnchorContent(string $softwareDescription): bool
    {
        return preg_match(AppSettings::ANCHOR_CONTENT_PATTERN, $softwareDescription) === 1;
    }
}
