<?php

/**
 * /view/dashboard.php
 *
 * Page du dashboard pour les admins
 *
 * @author A. Espinoza
 * @date 03/2023
 */

if (!isset($_SESSION['user'])) {
    header('Location: /erreur/');
} else {

    $title = 'Dashboard | Webylo Recrutement';
    include_once 'header.php';
?>
    <div class="container my-5 py-5" id="refresh">
        <div class="row mt-5 pt-5" id="nbI">
            <div class="col my-3">
                <a href="/candidat/" class="text-decoration-none">
                    <div class="nbInscriptions">
                        Nombre d'inscriptions &nbsp;&nbsp;<i class='bx bxs-user icon-dashT'></i>
                        <p class="text-start mt-3 numberI"><strong><?= CandidatManager::getNbCandidat(0) ?></strong></p>
                    </div>
                </a>
            </div>
            <div class="col my-3">
                <a href="/candidat/accepté/" class="text-decoration-none">
                    <div class="inscriptionsAccepte" id="inscriptionsAccepte">
                        Inscriptions acceptées &nbsp;&nbsp;<i class='bx bxs-user-check icon-dashA'></i>
                        <p class="text-start mt-3 numberI"><strong><?= CandidatManager::getNbCandidat(2) ?></strong></p>
                    </div>
                </a>
            </div>
            <div class="col my-3">
                <a href="/candidat/refusé/" class="text-decoration-none">
                    <div class="inscriptionsRefuse">
                        Inscriptions refusées &nbsp;&nbsp;<i class='bx bxs-user-x icon-dashR'></i>
                        <p class="text-start mt-3 numberI"><strong><?= CandidatManager::getNbCandidat(3) ?></strong></p>
                    </div>
                </a>
            </div>
            <div class="col my-3">
                <a href="/candidat/en-attente/" class="text-decoration-none">
                    <div class="inscriptionsAttente">
                        Inscriptions en attente &nbsp;&nbsp;<i class='bx bxs-hourglass icon-dashT'></i>
                        <p class="text-start mt-3 numberI"><strong><?= CandidatManager::getNbCandidat(1) ?></strong></p>
                    </div>
                </a>
            </div>
        </div>
        <div class="row mt-3 text-center mb-3">
            <div class="col">
                <a href="/poste/" class="btnBlue mt-2">Postes</a>
            </div>
            <div class="col">
                <a href="/utilisateur/" class="btnPink mt-2">Utilisateurs</a>
            </div>
            <div class="col">
                <a href="/candidat/" class="btnBlue mt-2">Candidats</a>
            </div>
            <div class="col">
                <a href="/statut/" class="btnPink mt-2">Statuts</a>
            </div>
        </div>

        <?php if ($_GET['controller'] == 'candidat') { ?>

            <?php if (isset($_GET['idS'])) { ?>
                <?php if ($_GET['idS'] == 1) { ?>

                    <div class="tableDash my-5">
                        <p class="fs-3">Inscriptions en attente</p>
                        <table class="table table-striped" data-toggle="table" id="enAtt" data-search="true" data-pagination="true" data-page-size="12">
                            <thead>
                                <tr>
                                    <th data-sortable="true" data-field="id">Num</th>
                                    <th data-sortable="true" data-field="nom">Nom</th>
                                    <th data-sortable="true" data-field="prenom">Prénom</th>
                                    <th data-sortable="true" data-field="email">Email</th>
                                    <th data-sortable="true" data-field="dateI">Date Inscription</th>
                                    <th data-sortable="true" data-field="poste">Poste</th>
                                    <th>Action</th>
                                    <th>Détail</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lesCandidats as $unCandidat) { ?>
                                    <tr>
                                        <td><?= $unCandidat->getId() ?></td>
                                        <td><?= $unCandidat->getNom() ?></td>
                                        <td><?= $unCandidat->getPrenom() ?></td>
                                        <td><?= $unCandidat->getEmail() ?></td>
                                        <td><?= $unCandidat->getDateInscription()->format('d/m/Y') ?></td>
                                        <td><?= $unCandidat->getDesiPoste() ?></td>
                                        <td><i data-id="<?= $unCandidat->getId() ?>" data-bs-toggle="modal" data-bs-target="#modalCandA" class='bx bx-check-circle actionsAcc ms-3' data-toggle="tooltip" title="Accepter"></i><i class='bx bx-x-circle actionsRef ms-3' data-bs-toggle="modal" data-bs-target="#modalCandR" data-id="<?= $unCandidat->getId() ?>" data-toggle="tooltip" title="Refuser"></i></td>
                                        <td><a href="/candidat/<?= $unCandidat->getId() ?>/" class="text-black">Plus d'informations</a></td>
                                    </tr>
                                <?php } ?>
                            </tbody>

                        </table>
                    </div>
                    <div id="modalCandA" class="modal fade" role="dialog">
                        <div class="modal-dialog modal-confirm">
                            <div class="modal-content">
                                <div class="modal-header flex-column">
                                    <div class="icon-boxA">
                                        <i class="bx bx-check"></i>
                                    </div>
                                    <h4 class="modal-title w-100">Êtes-vous sûr ?</h4>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <p>Voulez-vous vraiment accepter ce candidat ? Cela entraînera une acceptation définitif</p>
                                </div>
                                <div class="modal-footer justify-content-center">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <button type="button" class="btn btn-success" id="confirm-accepter">Accepter</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="modalCandR" class="modal fade" role="dialog">
                        <div class="modal-dialog modal-confirm">
                            <div class="modal-content">
                                <div class="modal-header flex-column">
                                    <div class="icon-box">
                                        <i class="bx bx-x material-icons"></i>
                                    </div>
                                    <h4 class="modal-title w-100">Êtes-vous sûr ?</h4>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <p>Voulez-vous vraiment refuser ce candidat ? Cela entraînera un refus définitif</p>
                                </div>
                                <div class="modal-footer justify-content-center">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <button type="button" class="btn btn-danger" id="confirm-refuser">Refuser</button>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php } else if ($_GET['idS'] == 2) { ?>

                    <div class="tableDash my-5">
                        <p class="fs-3">Inscriptions acceptées</p>
                        <table class="table table-striped" data-toggle="table" data-search="true" data-pagination="true" data-page-size="12">
                            <thead>
                                <tr>
                                    <th data-sortable="true" data-field="id">Num</th>
                                    <th data-sortable="true" data-field="nom">Nom</th>
                                    <th data-sortable="true" data-field="prenom">Prénom</th>
                                    <th data-sortable="true" data-field="email">Email</th>
                                    <th data-sortable="true" data-field="dateI">Date Inscription</th>
                                    <th data-sortable="true" data-field="poste">Poste</th>
                                    <th>Statut</th>
                                    <th>Détail</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lesCandidats as $unCandidat) { ?>
                                    <tr>
                                        <td><?= $unCandidat->getId() ?></td>
                                        <td><?= $unCandidat->getNom() ?></td>
                                        <td><?= $unCandidat->getPrenom() ?></td>
                                        <td><?= $unCandidat->getEmail() ?></td>
                                        <td><?= $unCandidat->getDateInscription()->format('d/m/Y') ?></td>
                                        <td><?= $unCandidat->getDesiPoste() ?></td>
                                        <td>
                                            <p class="<?= $unCandidat->getLibStatut() ?>"><?= $unCandidat->getLibStatut() ?></p>
                                        </td>
                                        <td><a href="/candidat/<?= $unCandidat->getId() ?>/" class="text-black">Plus d'informations</a></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } else if ($_GET['idS'] == 3) { ?>

                    <div class="tableDash my-5">
                        <div class="row">
                            <div class="col">
                                <p class="fs-3">Inscriptions refusées</p>
                            </div>
                            <div class="col text-end" id="ref">
                                <button class="btnRed delCandS ms-3" data-bs-toggle="modal" data-bs-target="#modalCSupp" data-id="3" data-toggle="tooltip" title="Supprimer les candidats refusées">Supprimer <i class='bx bx-trash'></i></button>
                            </div>
                        </div>

                        <table class="table table-striped" data-toggle="table" data-search="true" data-pagination="true" data-page-size="12">
                            <thead>
                                <tr>
                                    <th data-sortable="true" data-field="id">Num</th>
                                    <th data-sortable="true" data-field="nom">Nom</th>
                                    <th data-sortable="true" data-field="prenom">Prénom</th>
                                    <th data-sortable="true" data-field="email">Email</th>
                                    <th data-sortable="true" data-field="dateI">Date Inscription</th>
                                    <th data-sortable="true" data-field="poste">Poste</th>
                                    <th>Statut</th>
                                    <th>Détail</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lesCandidats as $unCandidat) { ?>
                                    <tr>
                                        <td><?= $unCandidat->getId() ?></td>
                                        <td><?= $unCandidat->getNom() ?></td>
                                        <td><?= $unCandidat->getPrenom() ?></td>
                                        <td><?= $unCandidat->getEmail() ?></td>
                                        <td><?= $unCandidat->getDateInscription()->format('d/m/Y') ?></td>
                                        <td><?= $unCandidat->getDesiPoste() ?></td>
                                        <td>
                                            <p class="<?= $unCandidat->getLibStatut() ?>"><?= $unCandidat->getLibStatut() ?></p>
                                        </td>
                                        <td><a href="/candidat/<?= $unCandidat->getId() ?>/" class="text-black">Plus d'informations</a></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <div id="modalCSupp" class="modal fade" role="dialog">
                        <div class="modal-dialog modal-confirm">
                            <div class="modal-content">
                                <div class="modal-header flex-column">
                                    <div class="icon-box">
                                        <i class="bx bx-trash"></i>
                                    </div>
                                    <h4 class="modal-title w-100">Êtes-vous sûr ?</h4>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <p>Voulez-vous vraiment supprimer tous les candidats refusées ?</p>
                                </div>
                                <div class="modal-footer justify-content-center">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <button type="button" class="btn btn-danger" id="confirm-delete">Supprimer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }
            } else { ?>

                <div class="tableDash my-5">
                    <div class="row">
                        <div class="col">
                            <p class="fs-3">Inscriptions</p>
                        </div>
                        <div class="col text-end">
                            <a href="/candidat/ajouter/" class="btn btn-dark">Ajouter</a>
                        </div>
                    </div>
                    <table class="table table-striped" data-toggle="table" data-search="true" data-pagination="true" data-page-size="12">
                        <thead>
                            <tr>
                                <th data-sortable="true" data-field="id">Num</th>
                                <th data-sortable="true" data-field="nom">Nom</th>
                                <th data-sortable="true" data-field="prenom">Prénom</th>
                                <th data-sortable="true" data-field="email">Email</th>
                                <th data-sortable="true" data-field="dateI">Date Inscription</th>
                                <th data-sortable="true" data-field="poste">Poste</th>
                                <th data-sortable="true" data-field="statut">Statut</th>
                                <th>Détail</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lesCandidats as $unCandidat) { ?>
                                <tr>
                                    <td><?= $unCandidat->getId() ?></td>
                                    <td><?= $unCandidat->getNom() ?></td>
                                    <td><?= $unCandidat->getPrenom() ?></td>
                                    <td><?= $unCandidat->getEmail() ?></td>
                                    <td><?= $unCandidat->getDateInscription()->format('d/m/Y') ?></td>
                                    <td><?= $unCandidat->getDesiPoste() ?></td>
                                    <td>
                                        <p class="<?= $unCandidat->getLibStatut() ?>"><?= $unCandidat->getLibStatut() ?></p>
                                    </td>
                                    <td><a href="/candidat/<?= $unCandidat->getId() ?>/" class="text-black">Plus d'informations</a></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
        <?php } ?>

        <?php if ($_GET['controller'] == 'poste') { ?>

            <div class="tableDash my-5">
                <div class="row">
                    <div class="col">
                        <p class="fs-3">Postes</p>
                    </div>
                    <div class="col text-end">
                        <a href="/poste/ajouter/" class="btn btn-dark">Ajouter</a>
                    </div>
                </div>
                <table class="table table-striped" data-toggle="table" data-search="true" data-pagination="true" data-page-size="12" id="tPoste">
                    <thead>
                        <tr>
                            <th data-sortable="true" data-field="id">Num</th>
                            <th data-sortable="true" data-field="libelle">Libelle</th>
                            <th data-sortable="true" data-field="designation">Désignation</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lesPostes as $unPoste) { ?>
                            <tr>
                                <td><?= $unPoste->getId() ?></td>
                                <td><?= $unPoste->getLibelle() ?></td>
                                <td><?= $unPoste->getDesignation() ?></td>
                                <td><a href="/poste/<?= $unPoste->getId() ?>/" class="actionsEdit" data-toggle="tooltip" title="Modifier"><i class='bx bx-edit'></i></a><i class='bx bx-trash ms-3 actionsDelete deletePoste' data-bs-toggle="modal" data-bs-target="#modalPoste" data-id="<?= $unPoste->getId() ?>" data-toggle="tooltip" title="Supprimer"></i><?php if ($unPoste->getDesactiver() == 0) { ?><i class='bx bx-power-off ms-3 actionsOff offPoste' data-bs-toggle="modal" data-bs-target="#modalPosteOff" data-id="<?= $unPoste->getId() ?>" data-toggle="tooltip" title="Désactiver"></i> <?php } else { ?><i class='bx bx-power-off ms-3 actionsOn onPoste' data-bs-toggle="modal" data-bs-target="#modalPosteOn" data-id="<?= $unPoste->getId() ?>" data-toggle="tooltip" title="Réactiver"></i><?php } ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div id="modalPoste" class="modal fade" role="dialog">
                <div class="modal-dialog modal-confirm">
                    <div class="modal-content">
                        <div class="modal-header flex-column">
                            <div class="icon-box">
                                <i class="bx bx-trash"></i>
                            </div>
                            <h4 class="modal-title w-100">Êtes-vous sûr ?</h4>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p>Voulez-vous vraiment supprimer ce poste ? Cela entraînera la suppression de tous les candidats ayant postulé pour ce poste</p>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="button" class="btn btn-danger" id="confirm-delete">Supprimer</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="modalPosteOff" class="modal fade" role="dialog">
                <div class="modal-dialog modal-confirm">
                    <div class="modal-content">
                        <div class="modal-header flex-column">
                            <div class="icon-box">
                                <i class='bx bx-power-off'></i>
                            </div>
                            <h4 class="modal-title w-100">Êtes-vous sûr ?</h4>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p>Voulez-vous vraiment fermer le recrutement pour ce poste ? Cela entraînera la suppression de tous les candidats refusés pour ce poste</p>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="button" class="btn btn-danger" id="confirm-desac">Désactiver</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="modalPosteOn" class="modal fade" role="dialog">
                <div class="modal-dialog modal-confirm">
                    <div class="modal-content">
                        <div class="modal-header flex-column">
                            <div class="icon-boxA">
                                <i class='bx bx-power-off'></i>
                            </div>
                            <h4 class="modal-title w-100">Êtes-vous sûr ?</h4>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p>Voulez-vous vraiment réouvrir le recrutement pour ce poste ?</p>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="button" class="btn btn-success" id="confirm-reactiver">Réactiver</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php if ($_GET['controller'] == 'utilisateur') { ?>

            <div class="tableDash my-5">
                <div class="row">
                    <div class="col">
                        <p class="fs-3">Utilisateurs</p>
                    </div>
                    <div class="col text-end">
                        <a href="/utilisateur/ajouter/" class="btn btn-dark">Ajouter</a>
                    </div>
                </div>
                <table class="table table-striped" data-toggle="table" data-search="true" data-pagination="true" data-page-size="12" id="tUtilisateur">
                    <thead>
                        <tr>
                            <th data-sortable="true" data-field="id">Num</th>
                            <th data-sortable="true" data-field="nom">Nom</th>
                            <th data-sortable="true" data-field="prenom">Prénom</th>
                            <th data-sortable="true" data-field="email">Email</th>
                            <th data-sortable="true" data-field="role">Rôle</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lesUtilisateurs as $unUtilisateur) { ?>
                            <tr>
                                <td><?= $unUtilisateur->getId() ?></td>
                                <td><?= $unUtilisateur->getNom() ?></td>
                                <td><?= $unUtilisateur->getPrenom() ?></td>
                                <td><?= $unUtilisateur->getEmail() ?></td>
                                <td><?= $unUtilisateur->getRole() ?></td>
                                <td><a href="/utilisateur/<?= $unUtilisateur->getId() ?>/" class="actionsEdit" data-toggle="tooltip" title="Modifier"><i class='bx bx-edit'></i></a><i class='bx bx-trash ms-3 actionsDelete deleteUtilisateur' data-bs-toggle="modal" data-bs-target="#modalUtilisateur" data-id="<?= $unUtilisateur->getId() ?>" data-toggle="tooltip" title="Supprimer"></i></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div id="modalUtilisateur" class="modal fade" role="dialog">
                <div class="modal-dialog modal-confirm">
                    <div class="modal-content">
                        <div class="modal-header flex-column">
                            <div class="icon-box">
                                <i class="bx bx-trash"></i>
                            </div>
                            <h4 class="modal-title w-100">Êtes-vous sûr ?</h4>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p>Voulez-vous vraiment supprimer cet utilisateur ?</p>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="button" class="btn btn-danger" id="confirm-delete">Supprimer</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php if ($_GET['controller'] == 'statut') { ?>

            <div class="tableDash my-5">
                <div class="row">
                    <div class="col">
                        <p class="fs-3">Statuts</p>
                    </div>
                    <div class="col text-end">
                        <a href="/statut/ajouter/" class="btn btn-dark">Ajouter</a>
                    </div>
                </div>
                <table class="table table-striped" data-toggle="table" data-search="true" data-pagination="true" data-page-size="12" id="tStatut">
                    <thead>
                        <tr>
                            <th data-sortable="true" data-field="id">Num</th>
                            <th data-sortable="true" data-field="libelle">Libelle</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lesStatuts as $unStatut) { ?>
                            <tr>
                                <td><?= $unStatut->getId() ?></td>
                                <td><?= $unStatut->getLibelle() ?></td>
                                <td><a href="/statut/<?= $unStatut->getId() ?>/" class="actionsEdit" data-toggle="tooltip" title="Modifier"><i class='bx bx-edit'></i></a><i class='bx bx-trash ms-3 actionsDelete deleteStatut' data-bs-toggle="modal" data-bs-target="#modalStatut" data-id="<?= $unStatut->getId() ?>" data-toggle="tooltip" title="Supprimer"></i></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div id="modalStatut" class="modal fade" role="dialog">
                <div class="modal-dialog modal-confirm">
                    <div class="modal-content">
                        <div class="modal-header flex-column">
                            <div class="icon-box">
                                <i class="bx bx-trash"></i>
                            </div>
                            <h4 class="modal-title w-100">Êtes-vous sûr ?</h4>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p>Voulez-vous vraiment supprimer ce statut ? Cela entraînera la suppression de tous les candidats ayant le statut supprimé</p>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="button" class="btn btn-danger" id="confirm-delete">Supprimer</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <!-- Début footer -->
    <?php
    include_once 'footer.php';
    ?>
    <!-- Fin footer -->

    <!-- JS Libraries -->
    <script type="text/javascript" src="/js/dashboard.js"></script>
    <script src="https://unpkg.com/bootstrap-table@1.21.3/dist/bootstrap-table.min.js"></script>
    <script src="https://unpkg.com/bootstrap-table@1.21.3/dist/locale/bootstrap-table-fr-FR.min.js"></script>

    </body>

    </html>
<?php } ?>