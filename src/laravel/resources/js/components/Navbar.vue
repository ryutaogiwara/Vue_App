<template>
  <nav class="navbar">
    <!-- aタグの代わりにRouterLinkを用いる。Getリクエストが発生せずvuerouterにて画面を切り替える。 -->
    <RouterLink class="navbar__brand" to="/">
      Vuesplash
    </RouterLink>

    <div class="navbar__menu">
      <div class="navbar__item">
        <button class="button">
          <i class="icon ion-md-add"></i>
          Submit a photo
        </button>
      </div>

      <span class="navbar__item">
        user name
      </span>

      <div class="navbar__item">
        <RouterLink class="button button__link" to="/login">
          Login / Regiser
        </RouterLink>
      </div>
    </div>
  </nav>
</template>
