import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

class AuditLogsScreen extends ConsumerStatefulWidget {
  const AuditLogsScreen({super.key});

  @override
  ConsumerState<AuditLogsScreen> createState() => _AuditLogsScreenState();
}

class _AuditLogsScreenState extends ConsumerState<AuditLogsScreen> {
  String _selectedType = 'All';
  DateTimeRange? _dateRange;
  
  final List<Map<String, dynamic>> _logs = [
    {'time': '2024-01-15 10:30:00', 'user': 'admin@demo.com', 'action': 'User Created', 'ip': '192.168.1.1', 'type': 'user'},
    {'time': '2024-01-15 09:45:00', 'user': 'system', 'action': 'Backup Completed', 'ip': '-', 'type': 'system'},
    {'time': '2024-01-15 08:20:00', 'user': 'security@demo.com', 'action': 'Login Failed', 'ip': '203.0.113.45', 'type': 'auth'},
    {'time': '2024-01-14 23:15:00', 'user': 'admin@demo.com', 'action': 'Role Updated', 'ip': '192.168.1.1', 'type': 'role'},
    {'time': '2024-01-14 18:30:00', 'user': 'attacker@example.com', 'action': 'Multiple Failed Logins', 'ip': '45.67.89.10', 'type': 'security'},
  ];

  List<Map<String, dynamic>> get _filteredLogs {
    return _logs.where((log) {
      if (_selectedType != 'All' && log['type'] != _selectedType) return false;
      if (_dateRange != null) {
        final logDate = DateTime.parse(log['time'].split(' ')[0]);
        if (logDate.isBefore(_dateRange!.start) || logDate.isAfter(_dateRange!.end)) return false;
      }
      return true;
    }).toList();
  }

  Future<void> _selectDateRange() async {
    final picked = await showDateRangePicker(
      context: context,
      firstDate: DateTime(2020),
      lastDate: DateTime.now(),
    );
    if (picked != null) {
      setState(() => _dateRange = picked);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Audit Logs'),
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
          if (_dateRange != null)
            Container(
              padding: const EdgeInsets.all(8),
              color: Colors.blue.shade50,
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text('${_dateRange!.start.toLocal().toString().split(' ')[0]} - ${_dateRange!.end.toLocal().toString().split(' ')[0]}'),
                  IconButton(
                    icon: const Icon(Icons.close, size: 16),
                    onPressed: () => setState(() => _dateRange = null),
                  ),
                ],
              ),
            ),
          Expanded(
            child: ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: _filteredLogs.length,
              itemBuilder: (context, index) {
                final log = _filteredLogs[index];
                return Card(
                  margin: const EdgeInsets.only(bottom: 12),
                  child: ListTile(
                    leading: CircleAvatar(
                      backgroundColor: _getTypeColor(log['type']),
                      child: Icon(_getTypeIcon(log['type']), color: Colors.white, size: 20),
                    ),
                    title: Text(log['action'], style: const TextStyle(fontWeight: FontWeight.bold)),
                    subtitle: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text('User: ${log['user']} • IP: ${log['ip']}'),
                        Text(log['time'], style: const TextStyle(fontSize: 12, color: Colors.grey)),
                      ],
                    ),
                    onTap: () => _showLogDetails(log),
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
        title: const Text('Filter Logs'),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            DropdownButtonFormField<String>(
              value: _selectedType,
              decoration: const InputDecoration(labelText: 'Log Type'),
              items: ['All', 'user', 'auth', 'security', 'system', 'role']
                  .map((type) => DropdownMenuItem(value: type, child: Text(type.toUpperCase()))).toList(),
              onChanged: (value) => setState(() => _selectedType = value ?? 'All'),
            ),
            const SizedBox(height: 16),
            ListTile(
              title: const Text('Date Range'),
              subtitle: Text(_dateRange != null 
                  ? '${_dateRange!.start.toLocal().toString().split(' ')[0]} - ${_dateRange!.end.toLocal().toString().split(' ')[0]}'
                  : 'Select date range'),
              trailing: const Icon(Icons.calendar_today),
              onTap: _selectDateRange,
            ),
          ],
        ),
        actions: [
          TextButton(
            onPressed: () {
              setState(() {
                _selectedType = 'All';
                _dateRange = null;
              });
              Navigator.pop(context);
            },
            child: const Text('Reset'),
          ),
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Apply'),
          ),
        ],
      ),
    );
  }

  void _showLogDetails(Map<String, dynamic> log) {
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
            _buildDetailRow('IP Address', log['ip']),
            _buildDetailRow('Time', log['time']),
            _buildDetailRow('Type', log['type'].toUpperCase()),
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
          SizedBox(width: 100, child: Text(label, style: const TextStyle(fontWeight: FontWeight.bold, color: Colors.grey))),
          Expanded(child: Text(value)),
        ],
      ),
    );
  }

  Color _getTypeColor(String type) {
    switch (type) {
      case 'user': return Colors.blue;
      case 'auth': return Colors.orange;
      case 'security': return Colors.red;
      case 'system': return Colors.green;
      default: return Colors.grey;
    }
  }

  IconData _getTypeIcon(String type) {
    switch (type) {
      case 'user': return Icons.person;
      case 'auth': return Icons.lock;
      case 'security': return Icons.security;
      case 'system': return Icons.computer;
      default: return Icons.info;
    }
  }
}