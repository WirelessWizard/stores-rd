require( "class" )

-------------------------------------------------------------------------------
-- A class to fetch network statistics and return it in JSON form -------------
-------------------------------------------------------------------------------
class "rdNetstats"

--Init function for object
function rdNetstats:rdNetstats()

	require('rdLogger');
	require('rdExternal');

	self.version 	= "1.0.0"
	self.json	= require("json")
	self.logger	= rdLogger()
	self.external	= rdExternal()
	self.debug	= true
end
        
function rdNetstats:getVersion()
	return self.version
end


function rdNetstats:getWifi()
	return self:_getWifi()
end

function rdNetstats:getEthernet()


end

function rdNetstats:mapEthWithMeshMac()
	return self:__mapEthWithMeshMac()
end

function rdNetstats:log(m,p)
	if(self.debug)then
		self.logger:log(m,p)
	end
end
--[[--
========================================================
=== Private functions start here =======================
========================================================
(Note they are in the pattern function <rdName>._function_name(self, arg...) and called self:_function_name(arg...) )
--]]--

function rdNetstats.__mapEthWithMeshMac(self)

	local m 	= {}
	local mesh  = 'mesh0'

	--Add the eth0 addy which is used as the key and we assume each device will at least have an eth0            
	io.input("/sys/class/net/eth0/address")                                                                      
	m['eth0']       = io.read("*line")
	
	local file_to_check = "/sys/class/net/" .. mesh .. "/address"

	--Check if file exists
	local f=io.open(file_to_check,"r")                                                   
    if f~=nil then 
		io.close(f)
	else
		m['mesh0'] = ""
		return self.json.encode(m)
	end

	--Also record if this node is a gateway or not
	m['gateway'] = 0
    local f=io.open('/tmp/gw',"r")
    if f~=nil then
  		m['gateway'] = 1
	end

	--Read the file now we know it exists
	io.input(file_to_check)
	local mac 	= io.read("*line")
	m['mesh0'] 	= mac
	return self.json.encode(m)
end


function rdNetstats._getWifi(self)
	self:log('Getting WiFi stats')
	local w 	= {}
	w['radios']	= {}
	
	--Add the eth0 addy which is used as the key and we assume each device will at least have an eth0            
	io.input("/sys/class/net/eth0/address")                                                                      
	w['eth0']       = io.read("*line")   
	
	local phy 	= nil
	local i_info	= {}
	
	local dev = self.external:getOutput("iw dev")
	for line in string.gmatch(dev, ".-\n")do
		
		line = line:gsub("^%s*(.-)%s*$", "%1")
		if(line:match("^phy#"))then
			--Get the interface number 
			phy = line:gsub("^phy#", '')
			w['radios'][phy]		={}
			w['radios'][phy]['interfaces']	={}
			w['radios'][phy]['info']	={}
		end
		if(line:match("^Interface "))then
			line = line:gsub("^Interface ", '')
			i_info['name']	= line
		end
		if(line:match("^addr "))then
			line = line:gsub("^addr ", '')
			i_info['mac']	= line
		end
		if(line:match("^ssid "))then
			line = line:gsub("^ssid ", '')
			i_info['ssid']	= line
		end
		if(line:match("^type "))then
			line = line:gsub("^type ", '')
			i_info['type']	= line
			local stations 	= self._getStations(self,i_info['name'])
			--This is our last search now we can add the info
			table.insert(w['radios'][phy]['interfaces'],{name= i_info['name'],mac = i_info['mac'], ssid = i_info['ssid'], type= i_info['type'],stations = stations})
		end
	end
	return self.json.encode(w)
end 


function rdNetstats._getStations(self,interface)

	self:log('Getting Stations connected to '..interface)
	local s 	= {}
	local s_info	= {}
	local want_these= {
				'inactive time', 'rx bytes','rx packets','tx packets', 'tx bytes', 'tx packets', 'tx retries', 'tx failed',
				'signal', 'avg', 'tx bitrate', 'rx bitrate', 'authorized', 'authenticated', 'preamble',
				'WMM/WME', 'MFP', 'TDLS peer'
			}
	local last_item = "TDLS peer"
	
	local dev = self.external:getOutput("iw dev "..interface.." station dump")
	for line in string.gmatch(dev, ".-\n")do	--split it up on newlines
		
		line = line:gsub("^%s*(.-)%s*$", "%1")  --remove leading and trailing spaces
		if(line:match("^Station"))then
			line = line:gsub("^Station-%s(.-)%s.+","%1")
			s_info['mac'] = line
		end
		
		for i, v in ipairs(want_these) do 
			local l = line
			if(line:match(v..":-%s"))then
				line  		= line:gsub(v..":-%s+","")
				if(line:match("avg:") == nil)then --avoid catching 'signal avg'
					s_info[v] 	= line
				end
				if(l:match(last_item))then
					--create a new table and insert it into the s table
					local new_info = {}
					for j,k in ipairs(want_these) do
						new_info[k] = s_info[k]
					end
					new_info['mac'] = s_info['mac']
					table.insert(s,new_info)
				end
			end
		end
	end
	return s
end
