def is_on_wsm_network()
	Socket.ip_address_list.each do |address|
		if (address.ip_address().start_with?('10.40.8.'))
			return true
		end
	end
	
	return false
end