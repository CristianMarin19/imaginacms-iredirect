<?php

use Illuminate\Support\Str;
use Modules\Iredirect\Entities\Redirect as Redirect;

try {
  $uri = Request::path();
  $decodedUri = urldecode($uri);

  $redirectRepository = app('Modules\Iredirect\Repositories\RedirectRepository');
  $params = ['filter' => ['from' => [$decodedUri, '/' . $decodedUri]]];
  $redirect = $redirectRepository->getItemsBy(json_decode(json_encode($params)))->first();

  if (!$redirect) {
    $paramsSearch = ['filter' => ['search' => '/*']];
    $redirects = $redirectRepository->getItemsBy(json_decode(json_encode($paramsSearch)));
    foreach ($redirects as $wildcardRedirect) {
      $pattern = preg_quote($wildcardRedirect->from, '/');
      $pattern = str_replace('\*', '.*', $pattern);
      if (preg_match("/^$pattern$/", $decodedUri)) {
        $redirect = $wildcardRedirect;
        $redirect->from = $decodedUri;
        break;
      }
    }
  }

  if (isset($redirect->from) && !empty($redirect->from)) {
    Route::redirect($redirect->from, Str::start($redirect->to, '/'), $redirect->redirect_type);
  }

  Route::any('find-redirect/{url}', function ($url) {
    $redirectRepository = app('Modules\Iredirect\Repositories\RedirectRepository');
    $decodedUrl = urldecode($url);
    $params = ['filter' => ['field' => 'from']];
    $redirect = $redirectRepository->getItem($decodedUrl, $params);

    if (!$redirect) {
      $paramsSearch = ['filter' => ['search' => '/*']];
      $redirects = $redirectRepository->getItemsBy(json_decode(json_encode($paramsSearch)));

      foreach ($redirects as $wildcardRedirect) {
        $pattern = preg_quote($wildcardRedirect->from, '/');
        $pattern = str_replace('\*', '.*', $pattern);
        if (preg_match("/^$pattern$/", $decodedUrl)) {
          $redirect = $wildcardRedirect;
          break;
        }
      }
    }

    if (isset($redirect->from) && !empty($redirect->from)) {
      try {
        return \Redirect::to('/' . $redirect->to, $redirect->redirect_type);
      } catch (\Throwable $t) {
        Log::error($t->getMessage());
      } catch (\Exception $e) {
        Log::error($e->getMessage());
      }
    } else {
      return abort(404);
    }
  })->where('url', '.*');
} catch (Exception $e) {
  \Log::error($e->getMessage());
}
