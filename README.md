bash"""

1- Creation de Branch en tant que collaborateur(dev ou testeur)
    -Develepper: dev exemple: dev1
    -Testeur: test exemple: test1

2- Pour les message de commit:
    -structure: type(scope): description , exemple: git commit -m "fix(auth): corrige l’erreur de connexion"
    -types: 
        - feat: nouvelle fonctionnalité
        - fix: correction d’un bug
        - docs: documentation
        - style: changement de style
        - refactor: refactoring
        - test: ajout de tests
        - chore: tâches de maintenance
    -scope: la partie du code modifiée
    -description: une description courte de la modification
    -exemples:
        - git commit -m "feat(auth): ajoute la fonctionnalité de connexion"
        - git commit -m "fix(auth): corrige l’erreur de connexion"
        - git commit -m "docs(auth): ajoute la documentation de connexion"
        - git commit -m "style(auth): change le style de connexion"
        - git commit -m "refactor(auth): refactore la fonction de connexion"
        - git commit -m "test(auth): ajoute les tests de connexion"
        - git commit -m "chore(auth): tâches de maintenance de connexion"

3