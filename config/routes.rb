Rails.application.routes.draw do
  match '/404', to: 'errors#file_not_found', via: :all
	match '/422', to: 'errors#unprocessable', via: :all
	match '/500', to: 'errors#internal_server_error', via: :all

  root 'welcome#index'

  if Rails.env.development?
  	get '/error/not-found', to:'errors#file_not_found'
  	get '/error/server', to:'errors#internal_server_error'
  	get '/error/unprocessable', to:'errors#unprocessable'
 	end
end
