function page (path) {
  return () => import(/* webpackChunkName: '' */ `~/pages/${path}`).then(m => m.default || m)
}

export default [
  { path: '/', name: 'welcome', component: page('welcome.vue') },

  { path: '/login', name: 'login', component: page('auth/login.vue') },
  // { path: '/register', name: 'register', component: page('auth/register.vue') },
  { path: '/password/reset', name: 'password.request', component: page('auth/password/email.vue') },
  { path: '/password/reset/:token', name: 'password.reset', component: page('auth/password/reset.vue') },
  { path: '/email/verify/:id', name: 'verification.verify', component: page('auth/verification/verify.vue') },
  { path: '/email/resend', name: 'verification.resend', component: page('auth/verification/resend.vue') },

  { path: '/home', name: 'home', component: page('projects/home.vue') },
  { path: '/fastplan', name: 'fastplan', component: page('projects/fastplan.vue') },
  { path: '/condensate', name: 'condensate', component: page('projects/condensate.vue') },
  { path: '/drygas', name: 'drygas', component: page('projects/drygas.vue') },
  { path: '/fastplanresult', name: 'fastplanresult', component: page('projects/fastplan_result.vue') },
  { path: '/monitoringresult', name: 'monitoringresult', component: page('projects/monitoring_result.vue') },
  { path: '/monitoringresult2', name: 'monitoringresult2', component: page('projects/monitoring_result2.vue') },
  { path: '/separator', name: 'separator', component: page('projects/separator.vue') },
  { path: '/separatorresult', name: 'separatorresult', component: page('projects/separator_result.vue') },
  { path: '/multipleplots', name: 'multipleplots', component: page('projects/plots.vue') },
  {
    path: '/settings',
    component: page('settings/index.vue'),
    children: [
      { path: '', redirect: { name: 'settings.profile' } },
      { path: 'theme', name: 'settings.theme', component: page('settings/theme.vue') },
      { path: 'removeproject', name: 'settings.removeproject', component: page('settings/removeproject.vue') },
      { path: 'profile', name: 'settings.profile', component: page('settings/profile.vue') },
      { path: 'password', name: 'settings.password', component: page('settings/password.vue') },
      { path: 'users', name: 'settings.users', component: page('settings/users.vue') },
      { path: 'updateuser', name: 'settings.updateuser', component: page('settings/updateuser.vue') },
      { path: 'license', name: 'settings.license', component: page('settings/license.vue') }
    ]
  },

  { path: '*', component: page('errors/404.vue') }
]
