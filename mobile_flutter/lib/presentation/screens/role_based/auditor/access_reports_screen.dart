import 'package:flutter/material.dart';

class AccessReportsScreen extends StatefulWidget {
  const AccessReportsScreen({super.key});

  @override
  State<AccessReportsScreen> createState() => _AccessReportsScreenState();
}

class _AccessReportsScreenState extends State<AccessReportsScreen> {
  String _selectedUser = 'All';
  String _selectedAction = 'All';
  DateTimeRange? _dateRange;
  
  final List<Map<String, dynamic>> _accessLogs = [
    {'user': 'admin@demo.com', 'action': 'Login', 'resource': 'System', 'time': '2024-01-15 10:30:00', 'ip': '192.168.1.1', 'status': 'Success'},
    {'user': 'admin@demo.com', 'action': 'User Create', 'resource': 'User Management', 'time': '2024-01-15 10:35:00', 'ip': '192.168.1.1', 'status': 'Success'},
    {'user': 'security@demo.com', 'action': 'Role Change', 'resource': 'Role Management', 'time': '2024-01-15 09:45:00', 'ip': '192.168.1.50', 'status': 'Success'},
    {'user': 'unknown', 'action': 'Login Failed', 'resource': 'Authentication', 'time': '2024-01-15 08:20:00', 'ip': '203.0.113.45', 'status': 'Failed'},
    {'user': 'user@demo.com', 'action': 'Data Export', 'resource': 'Reports', 'time': '2024-01-14 16:30:00', 'ip': '192.168.1.100', 'status': 'Success'},
  ];

  List<Map<String, dynamic>> get _filteredLogs {
    return _accessLogs.where((log) {
      if (_selectedUser != 'All' && log['user'] != _selectedUser) return false;
      if (_selectedAction != 'All' && log['action'] != _selectedAction) return false;
      if (_dateRange != null) {
        final logDate = DateTime.parse(log['time'].split(' ')[0]);
        if (logDate.isBefore(_dateRange!.start) || logDate.isAfter(_dateRange!.end)) return false;
      }
      return true;
    }).toList();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Access Reports'),
        actions: [
          IconButton(
            icon: const Icon(Icons.filter_list),
            onPressed: () => _showFilterDialog(),
          ),
          IconButton(
            icon: const Icon(Icons.download),
            onPressed: () {},
          ),
        ],
      ),
      body: Column(
        children: [
          Expanded(
            child: ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: _filteredLogs.length,
              itemBuilder: (context, index) {
                final log = _filteredLogs[index];
                return Card(
                  margin: const EdgeInsets.only(bottom: 8),
                  child: ListTile(
                    leading: CircleAvatar(
                      backgroundColor: log['status'] == 'Success' ? Colors.green : Colors.red,
                      child: Icon(log['status'] == 'Success' ? Icons.check : Icons.close, color: Colors.white),
                    ),
                    title: Text('${log['action']} - ${log['resource']}', style: const TextStyle(fontWeight: FontWeight.bold)),
                    subtitle: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text('User: ${log['user']} • IP: ${log['ip']}'),
                        Text(log['time'], style: const TextStyle(fontSize: 12, color: Colors.grey)),
                      ],
                    ),
                    trailing: Chip(
                      label: Text(log['status']),
                      backgroundColor: log['status'] == 'Success' ? Colors.green.shade100 : Colors.red.shade100,
                    ),
                    onTap: () => _showAccessDetail(log),
                  ),
                );
              },
            ),
          ),
        ],
      ),
    );
  }

  void _showFilterDialog() {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Filter Access Logs'),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            DropdownButtonFormField<String>(
              value: _selectedUser,
              items: [
                const DropdownMenuItem(value: 'All', child: Text('All Users')),
                ..._accessLogs.map((log) => DropdownMenuItem(value: log['user'], child: Text(log['user']))).toSet().toList(),
              ],
              onChanged: (value) => setState(() => _selectedUser = value!),
              decoration: const InputDecoration(labelText: 'User'),
            ),
            const SizedBox(height: 12),
            DropdownButtonFormField<String>(
              value: _selectedAction,
              items: const [
                DropdownMenuItem(value: 'All', child: Text('All Actions')),
                DropdownMenuItem(value: 'Login', child: Text('Login')),
                DropdownMenuItem(value: 'Login Failed', child: Text('Login Failed')),
                DropdownMenuItem(value: 'User Create', child: Text('User Create')),
                DropdownMenuItem(value: 'Role Change', child: Text('Role Change')),
                DropdownMenuItem(value: 'Data Export', child: Text('Data Export')),
              ],
              onChanged: (value) => setState(() => _selectedAction = value!),
              decoration: const InputDecoration(labelText: 'Action'),
            ),
          ],
        ),
        actions: [
          TextButton(
            onPressed: () {
              setState(() {
                _selectedUser = 'All';
                _selectedAction = 'All';
              });
              Navigator.pop(context);
            },
            child: const Text('Reset'),
          ),
          TextButton(onPressed: () => Navigator.pop(context), child: const Text('Apply')),
        ],
      ),
    );
  }

  void _showAccessDetail(Map<String, dynamic> log) {
    showModalBottomSheet(
      context: context,
      builder: (context) => Container(
        padding: const EdgeInsets.all(24),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(log['action'], style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
            const Divider(),
            _buildDetailRow('User', log['user']),
            _buildDetailRow('Resource', log['resource']),
            _buildDetailRow('IP Address', log['ip']),
            _buildDetailRow('Time', log['time']),
            _buildDetailRow('Status', log['status']),
            const SizedBox(height: 16),
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: () => Navigator.pop(context),
                child: const Text('Close'),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildDetailRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 12),
      child: Row(
        children: [
          SizedBox(width: 80, child: Text(label, style: const TextStyle(fontWeight: FontWeight.bold, color: Colors.grey))),
          Expanded(child: Text(value)),
        ],
      ),
    );
  }
}