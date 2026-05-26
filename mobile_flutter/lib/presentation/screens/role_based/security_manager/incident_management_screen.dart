import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../../data/models/incident_model.dart';
import '../../../providers/incident_provider.dart';
import '../../../widgets/common/custom_button.dart';
import '../../../widgets/common/custom_textfield.dart';

class IncidentManagementScreen extends ConsumerStatefulWidget {
  const IncidentManagementScreen({super.key});

  @override
  ConsumerState<IncidentManagementScreen> createState() => _IncidentManagementScreenState();
}

class _IncidentManagementScreenState extends ConsumerState<IncidentManagementScreen> {
  String _selectedSeverity = 'All';
  String _selectedStatus = 'All';
  bool _showCreateDialog = false;
  
  final _titleController = TextEditingController();
  final _descriptionController = TextEditingController();
  String _severity = 'medium';
  String _category = 'unauthorized_access';

  @override
  void initState() {
    super.initState();
    ref.read(incidentProvider.notifier).loadIncidents();
  }

  void _openCreateDialog() {
    _titleController.clear();
    _descriptionController.clear();
    _severity = 'medium';
    _category = 'unauthorized_access';
    setState(() => _showCreateDialog = true);
  }

  Future<void> _createIncident() async {
    if (_titleController.text.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please enter incident title')),
      );
      return;
    }

    final success = await ref.read(incidentProvider.notifier).createIncident({
      'title': _titleController.text,
      'description': _descriptionController.text,
      'severity': _severity,
      'category': _category,
    });

    if (success && mounted) {
      setState(() => _showCreateDialog = false);
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Incident created successfully')),
      );
    }
  }

  Future<void> _assignIncident(String id) async {
    // Show user selection dialog
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Assign Incident'),
        content: DropdownButtonFormField<String>(
          items: const [
            DropdownMenuItem(value: 'user1', child: Text('John Doe')),
            DropdownMenuItem(value: 'user2', child: Text('Jane Smith')),
            DropdownMenuItem(value: 'user3', child: Text('Mike Johnson')),
          ],
          onChanged: (value) {},
          decoration: const InputDecoration(labelText: 'Select Team Member'),
        ),
        actions: [
          TextButton(onPressed: () => Navigator.pop(context), child: const Text('Cancel')),
          TextButton(onPressed: () => Navigator.pop(context), child: const Text('Assign')),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final incidentState = ref.watch(incidentProvider);
    final incidents = incidentState.incidents.where((incident) {
      if (_selectedSeverity != 'All' && incident.severity != _selectedSeverity) return false;
      if (_selectedStatus != 'All' && incident.status != _selectedStatus) return false;
      return true;
    }).toList();

    return Scaffold(
      appBar: AppBar(
        title: const Text('Incident Management'),
        actions: [
          IconButton(
            icon: const Icon(Icons.filter_list),
            onPressed: () => _showFilterDialog(),
          ),
          IconButton(
            icon: const Icon(Icons.add),
            onPressed: _openCreateDialog,
          ),
        ],
      ),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(16),
            child: Row(
              children: [
                Expanded(
                  child: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 12),
                    decoration: BoxDecoration(
                      border: Border.all(color: Colors.grey.shade300),
                      borderRadius: BorderRadius.circular(8),
                    ),
                    child: DropdownButtonHideUnderline(
                      child: DropdownButton<String>(
                        value: _selectedSeverity,
                        isExpanded: true,
                        items: const [
                          DropdownMenuItem(value: 'All', child: Text('All Severities')),
                          DropdownMenuItem(value: 'critical', child: Text('Critical')),
                          DropdownMenuItem(value: 'high', child: Text('High')),
                          DropdownMenuItem(value: 'medium', child: Text('Medium')),
                          DropdownMenuItem(value: 'low', child: Text('Low')),
                        ],
                        onChanged: (value) => setState(() => _selectedSeverity = value!),
                      ),
                    ),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 12),
                    decoration: BoxDecoration(
                      border: Border.all(color: Colors.grey.shade300),
                      borderRadius: BorderRadius.circular(8),
                    ),
                    child: DropdownButtonHideUnderline(
                      child: DropdownButton<String>(
                        value: _selectedStatus,
                        isExpanded: true,
                        items: const [
                          DropdownMenuItem(value: 'All', child: Text('All Status')),
                          DropdownMenuItem(value: 'open', child: Text('Open')),
                          DropdownMenuItem(value: 'investigating', child: Text('Investigating')),
                          DropdownMenuItem(value: 'resolved', child: Text('Resolved')),
                        ],
                        onChanged: (value) => setState(() => _selectedStatus = value!),
                      ),
                    ),
                  ),
                ),
              ],
            ),
          ),
          Expanded(
            child: incidentState.isLoading
                ? const Center(child: CircularProgressIndicator())
                : ListView.builder(
                    padding: const EdgeInsets.all(16),
                    itemCount: incidents.length,
                    itemBuilder: (context, index) {
                      final incident = incidents[index];
                      return Card(
                        margin: const EdgeInsets.only(bottom: 12),
                        child: ExpansionTile(
                          leading: CircleAvatar(
                            backgroundColor: _getSeverityColor(incident.severity ?? 'medium'),
                            child: Text(incident.incidentCode?.substring(0, 3) ?? 'INC', style: const TextStyle(fontSize: 12, color: Colors.white)),
                          ),
                          title: Text(incident.title ?? 'Unknown', style: const TextStyle(fontWeight: FontWeight.bold)),
                          subtitle: Text('${incident.severity?.toUpperCase()} • ${incident.status}'),
                          trailing: Row(
                            mainAxisSize: MainAxisSize.min,
                            children: [
                              IconButton(
                                icon: const Icon(Icons.person_add, size: 20),
                                onPressed: () => _assignIncident(incident.id!),
                              ),
                              IconButton(
                                icon: const Icon(Icons.edit, size: 20),
                                onPressed: () {},
                              ),
                            ],
                          ),
                          children: [
                            Padding(
                              padding: const EdgeInsets.all(16),
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  const Text('Description:', style: TextStyle(fontWeight: FontWeight.bold)),
                                  const SizedBox(height: 4),
                                  Text(incident.description ?? 'No description'),
                                  const SizedBox(height: 12),
                                  const Text('Timeline:', style: TextStyle(fontWeight: FontWeight.bold)),
                                  const SizedBox(height: 4),
                                  Text('Reported: ${_formatDate(incident.reportedAt)}'),
                                  Text('Detected: ${_formatDate(incident.detectedAt)}'),
                                  if (incident.resolvedAt != null)
                                    Text('Resolved: ${_formatDate(incident.resolvedAt)}'),
                                  const SizedBox(height: 12),
                                  Row(
                                    children: [
                                      Expanded(
                                        child: CustomButton(
                                          text: 'Investigate',
                                          onPressed: () {},
                                          height: 40,
                                        ),
                                      ),
                                      const SizedBox(width: 12),
                                      Expanded(
                                        child: CustomButton(
                                          text: 'Resolve',
                                          onPressed: () {},
                                          height: 40,
                                          isOutlined: true,
                                        ),
                                      ),
                                    ],
                                  ),
                                ],
                              ),
                            ),
                          ],
                        ),
                      );
                    },
                  ),
          ),
        ],
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: _openCreateDialog,
        child: const Icon(Icons.add),
      ),
    );
  }

  void _showFilterDialog() {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Filter Incidents'),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            DropdownButtonFormField<String>(
              value: _selectedSeverity,
              decoration: const InputDecoration(labelText: 'Severity'),
              items: const [
                DropdownMenuItem(value: 'All', child: Text('All')),
                DropdownMenuItem(value: 'critical', child: Text('Critical')),
                DropdownMenuItem(value: 'high', child: Text('High')),
                DropdownMenuItem(value: 'medium', child: Text('Medium')),
                DropdownMenuItem(value: 'low', child: Text('Low')),
              ],
              onChanged: (value) => setState(() => _selectedSeverity = value!),
            ),
            const SizedBox(height: 16),
            DropdownButtonFormField<String>(
              value: _selectedStatus,
              decoration: const InputDecoration(labelText: 'Status'),
              items: const [
                DropdownMenuItem(value: 'All', child: Text('All')),
                DropdownMenuItem(value: 'open', child: Text('Open')),
                DropdownMenuItem(value: 'investigating', child: Text('Investigating')),
                DropdownMenuItem(value: 'resolved', child: Text('Resolved')),
              ],
              onChanged: (value) => setState(() => _selectedStatus = value!),
            ),
          ],
        ),
        actions: [
          TextButton(onPressed: () => Navigator.pop(context), child: const Text('Apply')),
        ],
      ),
    );
  }

  String _formatDate(DateTime? date) {
    if (date == null) return 'N/A';
    return '${date.day}/${date.month}/${date.year} ${date.hour}:${date.minute}';
  }

  Color _getSeverityColor(String severity) {
    switch (severity.toLowerCase()) {
      case 'critical': return Colors.red;
      case 'high': return Colors.orange;
      case 'medium': return Colors.yellow.shade700;
      default: return Colors.green;
    }
  }
}