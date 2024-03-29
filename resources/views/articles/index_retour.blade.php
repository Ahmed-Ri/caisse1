@extends('layouts.app')

@section('content')


<div class="container">

    @if($errors->has('error'))
    <div class="alert alert-danger mt-5">
        {{ $errors->first('error') }}
    </div>
    @endif
    @if (session('success'))
    <div class="alert alert-success"> {{ session('success') }} </div>
    @endif

   
    {{-- <a href="{{ route('card.index') }}"> <img src="/assets/images/caisse.png" alt=""><span class="badge bg-secondary mb-2 ">{{ Cart::count() }}</span> </a> --}}
        <a href="{{ route('card.index') }}"><button type="button" class="btn btn-primary text-light btn-sm "> Retour a la caisse <span class="badge bg-primary text-light">{{ Cart::count() }}</span></button></a>

   <div class="rechercheFlex">
    <form class="search" action="{{ route('articles.search') }}" method="GET">
        <div class="input-group mt-2 ">
            <input type="text" name="query" class="form-control" placeholder="Rechercher un article">
            {{-- <button  class="btn btn-primary " type="submit"><img src="/assets/images/search.png" alt="" ></button>  --}}
        </div>
    </form>
    <form class="trie" action="{{ route('articles.index') }}" method="GET">
        <select name="category" onchange="this.form.submit()" class="custom-dropdown">
            <option value="">Trier par catégorie</option>
            @foreach($categories as $category)
                <option value="{{ $category->nomCategorie }}" 
                    {{ (request('category') == $category->nomCategorie) ? 'selected' : '' }}>
                    {{ $category->nomCategorie }}
                </option>
            @endforeach
        </select>
    </form>
   <form action="{{ route('articles.index') }}" method="GET">
    <div class="stock-info">
        <p class="p1">Nombre d'article en stock: {{ $totalQuantity }}</p>
        <p class="p2">Valeur du stock: {{ $totalValue }} € TTC</p>
    </div>
   </form>
   </div>
    <!-- <a href="{{ route('card.index') }}"><button type="button" class="btn btn-warning text-light btn-sm ">< Retour a la caisse<span class="badge bg-warning text-danger">{{ Cart::count() }}</span></button>
</a> -->


  <div class="table-responsive">
  <table id="tabl" class="table table-secondary-emphasis table-striped mt-3 ">


<thead class="custom-thead">

    <tr>
        <th scope="col">Catégorie</th>
        <th scope="col">Sous Catégorie</th>
        <th scope="col">Réference</th>
        <th scope="col">Article</th>      
        <th scope="col">photo</th>
        <th scope="col">Marque</th>
        <th scope="col">Stock</th>
        <th scope="col">TTC</th>
        <th scope="col"></th>
        

    </tr>
</thead>
<tbody>



    @foreach($articles as $article)
    <tr>

        <td>{{ $article->sousCategorie->categorie->nomCategorie }}</td>
        <td>{{ $article->sousCategorie->nomSousCategorie }}</td>
        <td>{{ $article->reference }}</td>
        <td>{{ $article->nomArticle }}</td>     
        <td> <img src="{{ $article->photo }}" alt="" width="60" height="60" ></td>
        <td>{{ $article->marque }}</td>
        <td>{{ $article->stock }}</td>
        <td>{{ $article->getprix() }}</td>


        
      

        <td>
            <form action="{{ route('retourArticle') }}" method="post">
                @csrf
                <input type="hidden" name="id_article" value=" {{ $article->id }} ">
                <button type="submit" class="btn btn-primary btn-sm">ajouter</button>
            </form>
        </td>




    </tr>

    @endforeach



</tbody>

</table>
  </div>

</div>



@endsection

<script>
    var articles = @json($articles); // Passer les données des articles à JavaScript

    window.onload = function() {
        if (window.innerWidth <= 577) {
            var container = document.createElement('div');

            // Vérifier s'il y a des articles retournés
            if (articles.length > 0) {
                articles.forEach(function(article) {
                    var card = document.createElement('div');
                    card.className = 'article-card';

                    // Ajouter la photo, le prix et les boutons ici
                    var addToCartUrl = "{{ route('retourArticle') }}";
                    
                    card.innerHTML = `<div class="article-image">
                            <img src="${article.photo}" alt="${article.nomArticle}">
                        </div>
                        <div class="article-info">
                            <div class="article-nom">${article.nomArticle}</div>
                            <div class="article-prix">${article.prixTTC}€</div>
                        </div>
                        <div class="article-actions">
                            <form action="${addToCartUrl}" method="POST">
                                @csrf
                                <input type="hidden" name="id_article" value="${article.id}">
                                <button type="submit" class="btn btn-primary btn-sm">Ajouter</button>
                            </form>
                        </div>`;

                    container.appendChild(card);
                });
            } else {
                // Gérer le cas où aucun article n'est trouvé
                var noArticlesMessage = document.createElement('div');
                noArticlesMessage.innerText = 'Aucun article trouvé.';
                container.appendChild(noArticlesMessage);
            }

            document.body.appendChild(container);
        }
    };
</script>
