import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../providers/incident_provider.dart';
import '../../widgets/incident/incident_card.dart';

class IncidentListScreen extends ConsumerStatefulWidget {
  const IncidentListScreen({super.key});

  @override
  ConsumerState<IncidentListScreen> createState() => _IncidentListScreenState();
}

class _IncidentListScreenState extends ConsumerState<IncidentListScreen> {
  String _selectedFilter = 'All';

  @override
  void initState() {
    super.initState();
    ref.read(incidentProvider.notifier).loadIncidents();
  }

  @override
  Widget build(BuildContext context) {
    final state = ref.watch(incidentProvider);
    
    return Scaffold(
      appBar: AppBar(
        title: const Text('Incidents'),
        actions: [
          IconButton(
            icon: const Icon(Icons.filter_list),
            onPressed: () => _showFilterDialog(),
          ),
        ],
      ),
      body: state.isLoading
          ? const Center(child: CircularProgressIndicator())
          : ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: state.incidents.length,
              itemBuilder: (context, index) {
                final incident = state.incidents[index];
                return IncidentCard(incident: incident);
              },
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
              value: _selectedFilter,
              items: const [
                DropdownMenuItem(value: 'All', child: Text('All')),
                DropdownMenuItem(value: 'open', child: Text('Open')),
                DropdownMenuItem(value: 'investigating', child: Text('Investigating')),
                DropdownMenuItem(value: 'resolved', child: Text('Resolved')),
              ],
              onChanged: (value) => setState(() => _selectedFilter = value!),
              decoration: const InputDecoration(labelText: 'Status'),
            ),
          ],
        ),
        actions: [
          TextButton(onPressed: () => Navigator.pop(context), child: const Text('Apply')),
        ],
      ),
    );
  }
}