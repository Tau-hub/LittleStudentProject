 							# Structures 	

On a 4 structures différentes : 

1. Editor : 
   * row -> variable contenant la ligne acutelle du texte
   * column -> variable contenant la colonne actuelle du texte
   * grid -> buffer à 2 dimensions contenant le texte
   * size -> contient la taille de chaque ligne 
   * rowwin -> Indique la réelle ligne à l'affichage par rapport au texte (par exemple si on est à la ligne 2 sur l'affichage mais que la ligne de l'editeur correspond a 2*colmax alors rowwin est à 1)
   * name -> argv[1] si il y'a un argument NULL sinon

2. Windows :
   * row -> contient la ligne du curseur
   * column -> contient la colonne du curseur 
   * rowmax -> contient la ligne max de la fenêtre
   * colonnemax -> contient la colonne max de la fenêtre
3. Mouse : 
   * x -> position sur la ligne  de la souris 
   * y -> position sur la colonne de la souris
   * oldx -> ancienne position de la souris depuis le dernier clique gauche
   * oldy -> De même pour la colonne
4. Type :
   * Contient les 4 modes disponibles sur l'editeur, normal, remplacement, insertion, commande.    
  


							# Explication 
	
Pour une compréhension globale du code, j'ai surtout utilisé la taille de la ligne comme délimiteur pour afficher,
le traitement se fait dans différentes fonctions, l'affichage se fait uniquement dans refresh_windows() pour le texte,
et dans les fonctions input_commande ou input_normal concernant le mode commande " : " et le mode recherche " / ".  
Je stocke directement les "\n" dans mon buffer c'est pour cela que sur mes conditions des flèches il y'a une condition si : 
* la taille = 1(dans ce cas la ligne est vide, mais présente) et la taille > 1 (la ligne est présente, et non vide) 


							# Problemes et Solutions

Le plus gros problème a été les lignes qui dépassent la valeur colmax.  
Au début je n'avais pas la variable rowwin qui me permet actuellement de me situer dans mon editeur même si une ligne dépasse en taille cela donc m'a posé problème car je ne pouvais pas calculer à quelle ligne je me situais dans mon editeur lorsque une ligne était trop longue.   
Par ailleurs, avant, je remplissais une ligne jusqu'à  qu'elle atteigne colmax puis je faisais une nouvelle ligne dans mon editeur(Cette version est sur git, le mardi 30 mai).   
Il se peut qu'il reste certains bugs avec la taille trop longue des lignes.  
Hormis ce problème, le reste a été sans encombre. 

							# Ajouts 


 * Le scroll par touche ne se fait pas n'importe comment, les colonnes sont respectés et on ne peut pas dépasser le texte présent.
 * La fonction recherche avec / fonctionne, elle ne permet cependant que d'aller vers l'avant.
 * Le mode remplacement est disponible.






