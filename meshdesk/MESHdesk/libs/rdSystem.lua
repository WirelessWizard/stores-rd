require( "class" )

-------------------------------------------------------------------------------
-- Class used to set up the system settings handed from the config server -----
-- For now it only is the system password and hostname ------------------------
-- It will only do something if there was a change ----------------------------
-------------------------------------------------------------------------------
class "rdSystem"

--Init function for object
function rdSystem:rdSystem()
	require('rdLogger')
	local uci	    = require('uci')
	self.version    = "1.0.1"
	self.tag	    = "MESHdesk"
	self.debug	    = true
	self.json	    = require("json")
	self.logger	    = rdLogger()
	self.x		    = uci.cursor(nil,'/var/state')
	self.s			= "/etc/shadow"
	self.t			= "/tmp/t"
end
        
function rdSystem:getVersion()
	return self.version
end

function rdSystem:configureFromJson(file)
	self:log("==Configure System from JSON file "..file.."==")
	self:__configureFromJson(file)
end

function rdSystem:configureFromTable(tbl)
	self:log("==Configure System from  Lua table==")
	self:__configureFromTable(tbl)
end


function rdSystem:log(m,p)
	if(self.debug)then
		self.logger:log(m,p)
	end
end

--[[--
========================================================
=== Private functions start here =======================
========================================================
--]]--

function rdSystem.__configureFromJson(self,json_file)

	self:log("Configuring System from a JSON file")
	local contents 	= self:__readAll(json_file)
	local o			= self.json.decode(contents)
	if(o.config_settings.system ~= nil)then
		self:log("Found System settings - completing it")		
		self:__configureFromTable(o.config_settings.system)
	else
		self:log("No System settings found, please check JSON file")
	end
end

function rdSystem.__configureFromTable(self,tbl)

	--First we do the password
	local c_pw 		= self:__getCurrentPassword()
	if(c_pw)then
		local new_pw	= tbl.password_hash
		if(new_pw)then
			if(c_pw ~= new_pw)then
				self:__replacePassword(new_pw,'root')
			end
		end	
	end

	--Then we do the hostname
	local c_hn 	= self:__getCurrentHostname()
	if(c_hn)then
		local new_hn	= tbl.hostname
		if(new_hn)then
			if(c_hn ~= new_hn)then
				self:__setHostname(new_hn)
			end
		end
	end

	--Heartbeat interval
	local c_hb = self.x.get('meshdesk', 'settings', 'heartbeat_interval')
	if(c_hb)then
		local new_hb	= tbl.heartbeat_interval
		if(new_hb)then
			if(c_hb ~= new_hb)then
				self.x.set('meshdesk', 'settings', 'heartbeat_interval',new_hb)
				self.x.commit('meshdesk')
			end
		end
	end

	--Hearbeat dead after
	local c_hbd = self.x.get('meshdesk', 'settings', 'heartbeat_dead_after')
	if(c_hbd)then
		local new_hbd	= tbl.heartbeat_dead_after
		if(new_hbd)then
			if(c_hbd ~= new_hbd)then
				self.x.set('meshdesk', 'settings', 'heartbeat_dead_after',new_hbd)
				self.x.commit('meshdesk')
			end
		end
	end	 
end


function rdSystem.__getCurrentPassword(self,user)
    if(user == nil)then user = 'root' end
	local enc = nil
	for line in io.lines(self.s) do
		if(string.find(line,'^'..user..":.*"))then
			line = string.gsub(line, '^'..user..":", "")
			line = string.gsub(line, ":.*", "")
			--return line
			enc = line
			break
		end 
	end
	return enc
end


function rdSystem.__replacePassword(self,password,user)	
    if(user == nil)then user = 'root' end
	local new_shadow = '';
	for line in io.lines(self.s) do
		if(string.find(line,'^'..user..":.*"))then
			line = string.gsub(line, '^'..user..":.-:", "")
			line = user..":"..password..":"..line --Replace this line
		end
		new_shadow = new_shadow ..line.."\n"
	end

	local f,err = io.open(self.t,"w")
	if not f then return print(err) end
	f:write(new_shadow)
	f:close()
	os.execute("cp "..self.t.." "..self.s)
	os.remove(self.t)
end

function rdSystem.__getCurrentHostname(self)
	local hostname = nil
	self.x.foreach('system','system', 
		function(a)
			hostname = self.x.get('system', a['.name'], 'hostname')
	end)
	return hostname
end

function rdSystem.__setHostname(self,hostname)
	self.x.foreach('system','system', 
		function(a)
			self.x.set('system', a['.name'], 'hostname',hostname)
	end)
	self.x.commit('system')
	--Activate it
	os.execute("echo "..hostname.." > /proc/sys/kernel/hostname")	
end

function rdSystem.__readAll(self,file)
	local f = io.open(file,"rb")
	local content = f:read("*all")
	f:close()
	return content
end

