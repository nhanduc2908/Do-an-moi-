import 'package:flutter/material.dart';

class TeamManagementScreen extends StatefulWidget {
  const TeamManagementScreen({super.key});

  @override
  State<TeamManagementScreen> createState() => _TeamManagementScreenState();
}

class _TeamManagementScreenState extends State<TeamManagementScreen> {
  final List<Map<String, dynamic>> _teamMembers = [
    {'name': 'John Doe', 'role': 'Security Analyst', 'status': 'Online', 'avatar': 'JD', 'email': 'john@demo.com'},
    {'name': 'Jane Smith', 'role': 'Incident Responder', 'status': 'Busy', 'avatar': 'JS', 'email': 'jane@demo.com'},
    {'name': 'Mike Johnson', 'role': 'Security Analyst', 'status': 'Offline', 'avatar': 'MJ', 'email': 'mike@demo.com'},
    {'name': 'Sarah Williams', 'role': 'Incident Responder', 'status': 'Online', 'avatar': 'SW', 'email': 'sarah@demo.com'},
    {'name': 'Tom Brown', 'role': 'Security Analyst', 'status': 'Away', 'avatar': 'TB', 'email': 'tom@demo.com'},
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Team Management'),
        actions: [
          IconButton(
            icon: const Icon(Icons.person_add),
            onPressed: () {},
          ),
        ],
      ),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(16),
            child: TextField(
              decoration: InputDecoration(
                hintText: 'Search team members...',
                prefixIcon: const Icon(Icons.search),
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(8),
                ),
              ),
            ),
          ),
          Expanded(
            child: ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: _teamMembers.length,
              itemBuilder: (context, index) {
                final member = _teamMembers[index];
                return Card(
                  margin: const EdgeInsets.only(bottom: 12),
                  child: ListTile(
                    leading: CircleAvatar(
                      backgroundColor: Colors.blue.shade100,
                      child: Text(member['avatar'], style: const TextStyle(fontWeight: FontWeight.bold)),
                    ),
                    title: Text(member['name'], style: const TextStyle(fontWeight: FontWeight.bold)),
                    subtitle: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(member['role']),
                        Text(member['email'], style: const TextStyle(fontSize: 12)),
                      ],
                    ),
                    trailing: Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        Container(
                          width: 10,
                          height: 10,
                          decoration: BoxDecoration(
                            shape: BoxShape.circle,
                            color: _getStatusColor(member['status']),
                          ),
                        ),
                        const SizedBox(width: 8),
                        Text(member['status']),
                      ],
                    ),
                    onTap: () {},
                  ),
                );
              },
            ),
          ),
        ],
      ),
    );
  }

  Color _getStatusColor(String status) {
    switch (status) {
      case 'Online': return Colors.green;
      case 'Busy': return Colors.red;
      case 'Away': return Colors.orange;
      default: return Colors.grey;
    }
  }
}