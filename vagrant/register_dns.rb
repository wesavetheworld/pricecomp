module RegisterDNS
	
	class Plugin < Vagrant.plugin("2")
		name "RegisterDNS"

		config "registerdns" do
			Config
		end
		
		if (is_on_wsm_network() && defined?(BRIDGE_ADAPTER) && defined?(PUBLIC_DOMAIN))	
			%w{up reload}.each do |action|
				action_hook(:register_dns, "machine_action_#{action}".to_sym) do |hook|
					hook.append RegisterDNS::UpdateServer
				end
			end
		end
		
		guest_capability('linux', 'read_host_visible_ip_address') do
			RegisterDNS::GetIP
		end
	end
	
	class Config < Vagrant.plugin(2, :config)
	
		attr_accessor :machines, :public_domain
		
		def machines=(machines)
			@machines = machines
		end
		
		def public_domain=(public_domain)
			@public_domain = public_domain
		end
	
	end
	
	class GetIP
	
		def self.read_host_visible_ip_address(vm)
			command = "ifconfig  | grep 'inet addr:' | grep -v '127.0.0.1' | cut -d: -f2 | awk '{ print $1 }'"
			result  = ""
			vm.communicate.execute(command) do |type, data|
				result << data if type == :stdout
			end
			
			result.chomp.split("\n").last
		end
	end

	class UpdateServer
		def initialize(app, env)
			@app = app
			@env = env
		end
	
		def call(env)
			@env = env
			vm = env[:machine]
			machines = vm.config.registerdns.machines
			ip_address = vm.guest.capability(:read_host_visible_ip_address)
			env[:ui].info "Public IP Address: " + ip_address
			machines.each do |host|
				host = host.source if host.respond_to? :source
				host_entry = host.gsub(/\.localdev/, vm.config.registerdns.public_domain)
				env[:ui].info "Adding public host entry for '" + host_entry + "'"
				open('http://engdns.ldn.wsm.local:5000/?hostname=' + URI.escape(host_entry) + '&ip=' + ip_address)
			end
			@app.call(env)
		end
	end
	
end
