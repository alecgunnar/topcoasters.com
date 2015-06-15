Rails.application.routes.draw do
  match '/404', to: 'errors#file_not_found', via: :all
  match '/422', to: 'errors#unprocessable', via: :all
  match '/500', to: 'errors#internal_server_error', via: :all

  root 'welcome#index'

  get '/profile', to: 'profile#show'

  devise_for :user, :skip => [:sessions]

  as :user do
    get 'sign-in' => 'devise/sessions#new', :as => :new_user_session
    post 'sign-in' => 'devise/sessions#create', :as => :user_session
    delete 'sign-out' => 'devise/sessions#destroy', :as => :destroy_user_session
  end

  # Account Settings
  devise_scope :user do
    get '/account', to: 'users/account#main'

    scope '/account' do
      get '/profile', to: 'users/account#profile_data', :as => :settings_profile_data
      patch '/profile', to: 'users/account#profile_data'
    end
  end

  if Rails.env.development?
    get '/error/not-found', to:'errors#file_not_found'
    get '/error/server', to:'errors#internal_server_error'
    get '/error/unprocessable', to:'errors#unprocessable'
  end
end
